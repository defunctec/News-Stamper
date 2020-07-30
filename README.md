# News-Stamper
 Stamp News that find important to the Crown blockchain

## Setting up Crown
You need a VPS with (recommended) 2GB RAM, 1 CPU Core and 20GB hard drive.

Download and install the current Crown daemon and bootstrap to the server
```
sudo apt-get install curl -y && curl -s https://raw.githubusercontent.com/Crowndev/crowncoin/master/scripts/crown-server-install.sh | bash -s -b
```

Send a tiny amount of Crown to the daemon, for example (0.001). 

You need an existing protocol to create tokens. Create a new protocol editing the appropriate details
```
crown-cli nftproto register "Proto-short-name(abc)" "Proto-long-name" "YOUR-Crown-address" 2 "application/json" "" true true 255
```
eg
```
crown-cli nftproto register "abc" "alphabet" "CRWKKM8kYa6u5oKAtyjQ98uQqzfMXxD6g284" 2 "application/json" "" true true 255
```

In the VPS edit /root/.crown/crown.conf to include your RPC details


## Setting up the Plugin
First enter the "crown-con" folder and open "walletcon.php".
This will be where you store the connection(RPC) details to the Crown client.

Change to your details
```
CURLOPT_URL => "http://RPCUSER:RPCPASS:9341",
```

Change to your details
```
"user: RPCUSER:RPCPASS"
```

Now enter the "News-Stamper" folder and open "crown.php".

Change to your details
```
$servOwnerProto = "YOURPROTOCOL";
```

Change to your details
```
$servOwnerAddress = "ADMINOWNERADDRESS";
```

Change to your details
```
<a class="btn btn-danger" href="https://YOURURL/search/'.@$_GET['page_id'].'" id="submit_form">Get All list</a>
```

Now open "ajaxform.php".

Change to your details
```
curl_setopt($ch, CURLOPT_URL,"https://YOURURL/crown-con/walletcon.php");
```

Now open "searchme.php".

Change to your details
```
<button type="button" class="btn btn-primary"><a href="https://YOURURL/stamp">Create New Entry</a></button>
```

Place the crown-con folder inside the root web directory.

Place the News-Stamper folder inside wp-content/plugins.

Place searchme.php in the wp-content/theme directory.. 
This becomes a template you can choose when making a new page.
Call the page "Stamp" and choose the template "Search-News"

## Final touch
You need to add the plugin "Ultimate member" (free) to enable a custom box that users can fill in via registration and their user profile. This box adds the users address to the wordpress meta so we can call it automatically.
The code pulls this address when a user is logged in to save them re-entering it over again.

You need to call the box "get_user_crown_address" as meta.

Use the shortcode [news-stamper] to show the form.