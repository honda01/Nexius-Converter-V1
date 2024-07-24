<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }

    $user = $_SESSION['user'];
    echo 'Benvenuto, ' . htmlspecialchars($user['username']) . '!';
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convertitore Script QBCore <-> ESX</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: radial-gradient(circle, #0d0d0d, #000000);
            margin: 0;
            padding: 0;
            color: #e0e0e0;
        }
        h1 {
            color: #00c0ff;
            text-align: center;
            margin-bottom: 20px;
            font-size: 3em;
            text-shadow: 0 0 10px #00c0ff, 0 0 20px #00c0ff, 0 0 30px #00c0ff;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            background: rgba(30, 30, 30, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        textarea {
            width: calc(100% - 20px);
            height: 250px;
            padding: 12px;
            border: 1px solid #333;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.6);
            color: #e0e0e0;
            font-size: 16px;
            line-height: 1.5;
            resize: vertical;
            margin-bottom: 15px;
            margin-right: 20px; /* Add margin to the right */
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.8);
        }

        select, button {
            padding: 12px;
            margin: 5px 0;
            border-radius: 10px;
            border: 1px solid #444;
            font-size: 16px;
            box-sizing: border-box;
            width: 100%;
            cursor: pointer;
            background: linear-gradient(145deg, #1e2a38, #0d0d0d);
            color: #e0e0e0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }
        select:hover, button:hover {
            background: linear-gradient(145deg, #00c0ff, #0d0d0d);
            color: #1e2a38;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.7);
        }
        button {
            background: linear-gradient(145deg, #00c0ff, #0080ff);
            border: none;
            font-size: 16px;
            cursor: pointer;
            position: relative;
            color: #000;
            text-shadow: 0 0 5px #00c0ff;
        }
        button.copy-btn {
            background: linear-gradient(145deg, #4e5d6c, #3b4a5e);
        }
        button.copy-btn:hover {
            background: linear-gradient(145deg, #6a7a8c, #4a5d6c);
        }
        button.copy-btn::after {
            content: '';
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #fff;
        }
        button.copy-btn.copied::after {
            content: ' ✔ Copiato';
            display: inline;
        }
        h2 {
            color: #00c0ff;
            margin-top: 20px;
            font-size: 2em;
            text-shadow: 0 0 10px #00c0ff, 0 0 20px #00c0ff, 0 0 30px #00c0ff;
        }
        pre {
            background: rgba(0, 0, 0, 0.7);
            padding: 15px;
            border-radius: 10px;
            overflow: auto;
            white-space: pre-wrap;
            font-size: 16px;
            line-height: 1.5;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
        }
        p {
            font-size: 18px;
            text-align: center;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>QBCore <-> ESX Converter</h1>
        <p>DEVELOPED BY NEXIUS SERVICES</p>
        <textarea id="inputScript" placeholder="Insert the code here..."></textarea>
        <select id="conversionType">
            <option value="qbcToEsx">QBCore to ESX</option>
            <option value="esxToQbc">ESX to QBCore</option>
        </select>
        <div class="button-container">
            <button onclick="convertScript()">Convert</button>
            <button class="copy-btn" onclick="copyToClipboard()">Copy the code</button>
        </div>
        <h2>Code converted</h2>
        <pre id="outputScript"></pre>
    </div>

    <script>
        const conversions = {
            "qbcToEsx": {
                "QBCore.Functions.Notify": "ESX.showNotification",
                "QBCore.Functions.Progressbar": "ESX.UI.Menu.Open",
                "QBCore.Functions.GetPlayerData": "ESX.GetPlayerData",
                "QBCore.Functions.GetClosestPed": "ESX.Game.GetClosestPed",
                "QBCore.Functions.GetClosestVehicle": "ESX.Game.GetClosestVehicle",
                "QBCore.Functions.CreateCallback": "ESX.RegisterServerCallback",
                "QBCore.Functions.CreateUseableItem": "ESX.RegisterUsableItem",
                "QBCore.Player.Save": "ESX.SavePlayer",
                "QBCore.Functions.DeleteVehicle": "ESX.Game.DeleteVehicle",
                "QBCore.Functions.GetPlayers": "ESX.GetPlayers",
                "QBCore.Functions.GetPlayer": "ESX.GetPlayerFromId",
                "QBCore.Functions.GetPlayerByCitizenId": "ESX.GetPlayerFromIdentifier",
                "QBCore.Functions.SetVehicleProperties": "ESX.Game.SetVehicleProperties",
                "QBCore.Functions.SpawnVehicle": "ESX.Game.SpawnVehicle",
                "QBCore.Functions.SetPlayerData": "ESX.SetPlayerData",
                "QBCore.Functions.GetItemByName": "xPlayer.getInventoryItem",
                "QBCore.Functions.GetItemByName": "xPlayer.getWeapon",
                "QBCore.Functions.UseItem": "ESX.UseItem",
                "QBCore.Functions.GetClosestObject": "ESX.Game.GetClosestObject",
                "QBCore.Functions.GetClosestPlayer": "ESX.Game.GetClosestPlayer",
                "QBCore.Functions.GetVehicles": "ESX.Game.GetVehicles",
                "QBCore.Functions.GetClosestPed": "ESX.Game.GetClosestPed",
                "QBCore.Functions.GetClosestVehicle": "ESX.Game.GetClosestVehicle",
                "QBCore.Functions.SpawnObject": "ESX.Game.SpawnObject",
                "QBCore.Functions.Teleport": "ESX.Game.Teleport",
                "QBCore.Functions.GetPlayersInArea": "ESX.Game.GetPlayersInArea",
                "QBCore.Functions.GetVehicleInDirection": "ESX.Game.GetVehicleInDirection",
                "QBCore.Functions.GetVehiclesInArea": "ESX.Game.GetVehiclesInArea",
                "QBCore.Functions.GetPeds": "ESX.Game.GetPeds",
                "QBCore.Functions.GetObjects": "ESX.Game.GetObjects",
                "QBCore.Functions.SpawnLocalObject": "ESX.Game.SpawnLocalObject",
                "QBCore.Functions.SpawnLocalVehicle": "ESX.Game.SpawnLocalVehicle",
                "QBCore.Functions.RegisterPedheadshot": "ESX.Game.GetPedMugshot",
                "QBCore.Functions.SetEntityCoords": "ESX.Game.Teleport",
                "QBCore.Functions.SetEntityHeading": "ESX.Game.Teleport",
                "QBCore.Functions.GetVehicleProperties": "ESX.Game.SetVehicleProperties",
                "QBCore.Functions.GetTotalWeight": "xPlayer.getWeight",
                "QBCore.Functions.Kick": "xPlayer.kick",
                "QBCore.Functions.AddItem": "xPlayer.addInventoryItem",
                "QBCore.Functions.RemoveItem": "xPlayer.removeInventoryItem",
                "QBCore.Functions.SetJob": "xPlayer.setJob",
                "QBCore.Functions.GetJob": "xPlayer.getJob",
                "QBCore.Functions.GetName": "xPlayer.getName",
                "QBCore.Functions.SetName": "xPlayer.setName",
                "QBCore.Functions.SetWeaponTint": "xPlayer.setWeaponTint",
                "QBCore.Functions.GetMoney": "xPlayer.getMoney",
                "QBCore.Functions.AddMoney": "xPlayer.addMoney",
                "QBCore.Functions.RemoveMoney": "xPlayer.removeMoney",
                "QBCore.Functions.SetMaxWeight": "xPlayer.setMaxWeight",
                "QBCore.Functions.TriggerCallback": "ESX.TriggerServerCallback",
                "QBCore = exports['qb-core']:GetCoreObject()": "ESX = exports['es_extended']:getSharedObject()"
            },
            "esxToQbc": {
                "ESX.showNotification": "QBCore.Functions.Notify",
                "ESX.UI.Menu.Open": "QBCore.Functions.Progressbar",
                "ESX.GetPlayerData": "QBCore.Functions.GetPlayerData",
                "ESX.Game.GetClosestPed": "QBCore.Functions.GetClosestPed",
                "ESX.Game.GetClosestVehicle": "QBCore.Functions.GetClosestVehicle",
                "ESX.RegisterServerCallback": "QBCore.Functions.CreateCallback",
                "ESX.RegisterUsableItem": "QBCore.Functions.CreateUseableItem",
                "ESX.SavePlayer": "QBCore.Player.Save",
                "ESX.Game.DeleteVehicle": "QBCore.Functions.DeleteVehicle",
                "ESX.GetPlayers": "QBCore.Functions.GetPlayers",
                "ESX.GetPlayerFromId": "QBCore.Functions.GetPlayer",
                "ESX.GetPlayerFromIdentifier": "QBCore.Functions.GetPlayerByCitizenId",
                "ESX.Game.SetVehicleProperties": "QBCore.Functions.SetVehicleProperties",
                "ESX.Game.SpawnVehicle": "QBCore.Functions.SpawnVehicle",
                "ESX.SetPlayerData": "QBCore.Functions.SetPlayerData",
                "xPlayer.getInventoryItem": "QBCore.Functions.GetItemByName",
                "xPlayer.getWeapon": "QBCore.Functions.GetItemByName",
                "xPlayer.getWeaponComponent": "QBCore.Functions.GetItemByName",
                "xPlayer.getAmmo": "QBCore.Functions.GetItemByName",
                "xPlayer.setMoney": "QBCore.Functions.SetMoney",
                "xPlayer.getWeight": "QBCore.Functions.GetTotalWeight",
                "xPlayer.kick": "QBCore.Functions.Kick",
                "xPlayer.addInventoryItem": "QBCore.Functions.AddItem",
                "xPlayer.removeInventoryItem": "QBCore.Functions.RemoveItem",
                "xPlayer.removeInventoryItem": "QBCore.Functions.UseItem",
                "xPlayer.getWeapon": "QBCore.Functions.GetItemByName",
                "xPlayer.getWeaponComponent": "QBCore.Functions.GetItemByName",
                "xPlayer.getAmmo": "QBCore.Functions.GetItemByName",
                "xPlayer.setJob": "QBCore.Functions.SetJob",
                "xPlayer.getJob": "QBCore.Functions.GetJob",
                "xPlayer.getName": "QBCore.Functions.GetName",
                "xPlayer.setName": "QBCore.Functions.SetName",
                "xPlayer.setWeaponTint": "QBCore.Functions.SetWeaponTint",
                "xPlayer.getMoney": "QBCore.Functions.GetMoney",
                "xPlayer.addMoney": "QBCore.Functions.AddMoney",
                "xPlayer.removeMoney": "QBCore.Functions.RemoveMoney",
                "xPlayer.setMaxWeight": "QBCore.Functions.SetMaxWeight",
                "ESX.TriggerServerCallback": "QBCore.Functions.TriggerCallback",
                "ESX = exports['es_extended']:getSharedObject()": "QBCore = exports['qb-core']:GetCoreObject()"
            }
            // Uncomment and add SQL conversions here if needed
            // "sqlConversions": {
            //     "ghmattimysql:execute": "exports.oxmysql:execute",
            //     "ghmattimysql:executeSync": "exports.oxmysql:executeSync",
            //     "ghmattimysql:scalar": "exports.oxmysql:scalar",
            //     "ghmattimysql:scalarSync": "exports.oxmysql:scalarSync",
            //     "ghmattimysql:fetch": "exports.oxmysql:fetch",
            //     "ghmattimysql:insert": "exports.oxmysql:insert",
            //     "MySQL.Async.execute": "exports.oxmysql:execute",
            //     "MySQL.Async.fetchAll": "exports.oxmysql:execute",
            //     "MySQL.Sync.fetchAll": "exports.oxmysql:executeSync",
            //     "MySQL.Async.fetchScalar": "exports.oxmysql:scalar",
            //     "MySQL.Async.insert": "exports.oxmysql:insert"
            // }
        };




        function convertScript() {
            let script = document.getElementById('inputScript').value;
            let conversionType = document.getElementById('conversionType').value;
            let convertedScript = script;

            const map = conversions[conversionType];

            for (const [from, to] of Object.entries(map)) {
                let regex = new RegExp(`\\b${from}\\b`, 'g');
                convertedScript = convertedScript.replace(regex, to);
            }

            document.getElementById('outputScript').textContent = convertedScript;
        }

        function copyToClipboard() {
            const output = document.getElementById('outputScript').textContent;
            navigator.clipboard.writeText(output).then(() => {
                const copyButton = document.querySelector('.copy-btn');
                copyButton.classList.add('copied');
                setTimeout(() => {
                    copyButton.classList.remove('copied');
                }, 2000);
            }).catch(err => {
                console.error('error:', err);
            });
        }
    </script>
</body>
</html>
