@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../gnugat/nomo-spaco/bin/nomo-spaco
php "%BIN_TARGET%" %*
