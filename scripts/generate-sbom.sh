#!/bin/bash

# Script de génération SBOM (CycloneDX-like) pour ReviewMe
# Ce script extrait les dépendances directes pour la traçabilité.

OUTPUT="sbom.json"
DATE=$(date -u +"%Y-%m-%dT%H:%M:%SZ")

echo "--- Génération de la SBOM ReviewMe ($DATE) ---"

# Début du JSON
cat <<EOF > $OUTPUT
{
  "bomFormat": "CycloneDX",
  "specVersion": "1.4",
  "version": 1,
  "metadata": {
    "timestamp": "$DATE",
    "project": {
      "name": "ReviewMe",
      "version": "1.0.0"
    }
  },
  "components": [
EOF

# Extraction PHP (Direct Dependencies)
echo "  Processing PHP dependencies..."
php composer.phar show --direct --format=json | jq -r '.installed[] | "    {\"name\": \"\(.name)\", \"version\": \"\(.version)\", \"type\": \"library\", \"purl\": \"pkg:composer/\(.name)@\(.version)\"},"' >> $OUTPUT 2>/dev/null || \
grep -E '"(laravel|livewire)/[^"]+"' composer.json | sed -E 's/.*"(.*)": "(.*)".*/    {"name": "\1", "version": "\2", "type": "library"},/' >> $OUTPUT

# Extraction JS (Direct Dependencies)
echo "  Processing JS dependencies..."
# On extrait les clés des blocs "devDependencies" et "dependencies"
grep -E '"@?[a-z0-9/-]+": "(\^|~|>=|>|<|=)?[0-9].*"' package.json | while read -r line; do
    NAME=$(echo $line | cut -d'"' -f2)
    VERSION=$(echo $line | cut -d'"' -f4)
    # On évite les clés qui ne sont pas des packages
    if [[ "$NAME" != "node" && "$NAME" != "npm" && "$NAME" != "type" ]]; then
        echo "    {\"name\": \"$NAME\", \"version\": \"$VERSION\", \"purl\": \"pkg:npm/$NAME@$VERSION\"}," >> $OUTPUT
    fi
done

# Suppression de la virgule du dernier élément et fermeture
sed -i '' '$ s/,$//' $OUTPUT
echo "  ]" >> $OUTPUT
echo "}" >> $OUTPUT

echo "+++ SBOM générée avec succès dans $OUTPUT +++"
