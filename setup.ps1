# Script d'installation automatique pour ReviewMe
Write-Host "--- Configuration de l'environnement ReviewMe ---" -ForegroundColor Cyan

# 1. Vérification / Installation de PHP 8.3 via Winget
if (!(Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "PHP non trouve. Installation en cours..." -ForegroundColor Yellow
    winget install PHP.PHP.8.3 --accept-source-agreements --accept-package-agreements
}

# 2. Configuration du PATH (Utilisateur)
$phpPath = (Get-ChildItem -Path $env:LOCALAPPDATA\Microsoft\WinGet\Packages -Filter php.exe -Recurse | Select-Object -First 1).DirectoryName
if ($phpPath) {
    $currentPath = [System.Environment]::GetEnvironmentVariable("Path", "User")
    if ($currentPath -notlike "*$phpPath*") {
        [System.Environment]::SetEnvironmentVariable("Path", $currentPath + ";$phpPath", "User")
        Write-Host "PATH mis a jour. Veuillez redemarrer votre terminal/IDE." -ForegroundColor Green
    }
}

# 3. Installation des dépendances
Write-Host "Installation des dependances..." -ForegroundColor Cyan
composer install
npm install

# 4. Base de données
if (!(Test-Path "database/database.sqlite")) {
    New-Item -ItemType File -Path "database/database.sqlite" -Force
    # Utiliser le chemin direct pour migrer si PHP n'est pas encore dans le PATH
    $phpExe = (Get-ChildItem -Path $env:LOCALAPPDATA\Microsoft\WinGet\Packages -Filter php.exe -Recurse | Select-Object -First 1).FullName
    & $phpExe artisan migrate --seed
}

Write-Host "--- Configuration terminee ! ---" -ForegroundColor Green
