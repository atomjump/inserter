<img src="https://atomjump.com/images/logo80.png">

# inserter
A basic message inserter for AtomJump Messaging with a simple passcode for security.

 
## Requirements

AtomJump Loop Server >= 1.0.4


## Installation

Find the server at https://github.com/atomjump/loop-server. Download and install.

Download the .zip file or git clone the repository into the directory loop-server/plugins/inserter

Copy config/configORIGINAL.json to config/config.json

Edit the config file to match your own email account sender, and default forum to insert messages to.

Note: we recommend that you only use this plugin if you are on a secure HTTPS server. Otherwise, the passcode 
can potentially be intercepted, as it is unencrypted.


## Usage

You can then make an external API call to e.g.

```
https://yourdomain.com/loop-server/plugins/inserter/index.php?forum=[Forum Name]&msg=[Message URL-encoded]&code=2498jfknf-changeme
```

The response is a JSON array and includes "success" (true / false), and "msg" with the corresponding human message.