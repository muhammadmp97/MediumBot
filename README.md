# MediumBot
A contact bot to hide your personal account from people.

## Installation
1. Clone the project:  
`git clone https://github.com/WebPajooh/MediumBot`
2. Edit `resources/settings.json` .
3. Upload the files to your server and use [setWebhook](https://core.telegram.org/bots/api#setwebhook) to make a connection between your bot and the script. Your URL should be something like this: `https://example.com/some_directory/src/bot.php`.
   
That's it! 😌

## Settings
| Key | Description | Example |
|--|--|--|
| bot_token | Your token | 123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11
owner_id | Your ID | 301126514
language | Bot language | english
start_message | The message to show for /start command | Hello!\nHow can I help you?
offline_message | The message to show in offline mode | Sorry... I can't answer your questions now.
protect_content | Protects the contents of the sent message from forwarding and saving | true
debug_mode | Sends error messages | false

## Commands

| Command | Description | Public |
|--|--|--|
| `/start` | Shows a starting message to the user | Yes |
| `!block` | Blocks the user whose message is replied | No |
| `!unblock` | Unblocks the user whose message is replied | No |
| `!setstartmessage` | Updates the starting message to replied message | No |
| `!offline` | Actives offline mode and updates the offline mode message to replied message | No |
| `!online` | Deactivates offline mode and bot will be able to receive messages | No |

## Translations
Currently, we support English, Arabic and Persian. The translation files are simple json files located in `resources/translations` directory, containing some strings used by bot. If you needed to customize yours, just edit the language file and upload it to your server.

## ⚠️ Issues  or  Pull Requests
Feel comfortable to report bugs, but we're strict about merging pull requests add new features because we prefer keeping it as simple as possible.
