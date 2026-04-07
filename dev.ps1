# Script de lancement de développement pour ReviewMe
Write-Host "--- Lancement de ReviewMe ---" -ForegroundColor Cyan

# Trouver PHP si pas dans le PATH
$phpExe = Get-Command php -ErrorAction SilentlyContinue | Select-Object -ExpandProperty Source
if (!$phpExe) {
    Write-Host "Recherche de PHP dans les dossiers locaux..." -ForegroundColor Gray
    $phpExe = (Get-ChildItem -Path $env:LOCALAPPDATA\Microsoft\WinGet\Packages -Filter php.exe -Recurse | Select-Object -First 1).FullName
}

if (!$phpExe) {
    Write-Host "ERREUR : PHP n'est pas installe. Lancez ./setup.ps1 d'abord." -ForegroundColor Red
    exit
}

Write-Host "PHP trouve : $phpExe" -ForegroundColor Gray

# Lancer Artisan en arrière-plan
Start-Job -Name "LaravelBackend" -ScriptBlock { param($p) & $p artisan serve } -ArgumentList $phpExe

Write-Host "Backend en cours : http://localhost:8000" -ForegroundColor Green

# Lancer Vite
Write-Host "Frontend (Vite) en cours de lancement..." -ForegroundColor Green
npm run dev
