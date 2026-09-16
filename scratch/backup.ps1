$exclude = @('node_modules', '.git', 'akash_ff_panel.zip', '_backup_portfolio', 'scratch')
$items = Get-ChildItem -Path . | Where-Object { $exclude -notcontains $_.Name } | Select-Object -ExpandProperty FullName
Compress-Archive -Path $items -DestinationPath "akash_ff_panel.zip" -Force
Write-Host "Archive created successfully:"
Get-Item "akash_ff_panel.zip" | Select-Object Name, Length
