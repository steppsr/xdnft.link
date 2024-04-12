<?php
/*
     ██╗  ██╗██████╗ ███╗   ██╗███████╗████████╗    ██╗    ██╗██╗███████╗ █████╗ ██████╗ ██████╗ 
     ╚██╗██╔╝██╔══██╗████╗  ██║██╔════╝╚══██╔══╝    ██║    ██║██║╚══███╔╝██╔══██╗██╔══██╗██╔══██╗
      ╚███╔╝ ██║  ██║██╔██╗ ██║█████╗     ██║       ██║ █╗ ██║██║  ███╔╝ ███████║██████╔╝██║  ██║
      ██╔██╗ ██║  ██║██║╚██╗██║██╔══╝     ██║       ██║███╗██║██║ ███╔╝  ██╔══██║██╔══██╗██║  ██║
     ██╔╝ ██╗██████╔╝██║ ╚████║██║        ██║       ╚███╔███╔╝██║███████╗██║  ██║██║  ██║██████╔╝
     ╚═╝  ╚═╝╚═════╝ ╚═╝  ╚═══╝╚═╝        ╚═╝        ╚══╝╚══╝ ╚═╝╚══════╝╚═╝  ╚═╝╚═╝  ╚═╝╚═════╝ 
                                                                              https://xdnft.link
*/

session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>XDNFT Wizard</title>
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,800,800i" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <style>
			.powered-by { font-size:0.7rem; padding-top: 15px; }
			.input-form { vertical-align: top; width: 924px; }
			.input-form label { vertical-align: top; }
			.input-form textarea, .input-form input { border: 2px solid #0B0242; border-radius: 10px; padding-left:10px;padding-right:10px;padding-top:2px;padding-bottom:2px; }
			.submit-button { float:right; margin-right: -15px; }
			.nft-thumbnail { float:right; }
			.nft-thumbnail img { width: 150px; border: 2px solid #0B0242; }
			.nft-table { width:935px; border: 2px solid #0B0242; border-radius: 10px; padding: 30px; margin-top: 20px; display:inline-block; }
			.nft-table td { padding-right: 25px; font-size: 0.8rem; }
			.nft-table img {  }
			pre { border: 2px solid #0B0242; border-radius: 10px; padding: 15px; }
			.provenance { width:200px; padding: 2px; float:right; font-size:0.8rem; display:block; }
			.provenanceitem { display:block; padding-bottom: 8px; }
			.provenancetitle { font-weight:bold; font-size:1.2rem; }
        </style>
    </head>
    <body id="page-top">
<?php
$results = "<br><br>";
$nftids[] = "";
if(isset($_POST['submit']) && $_POST['submit'] == 'Submit')
{
	if(isset($_POST['act']))
	{
		$action = $_POST['act'];
		switch($action)
		{
			case "nftdetails":
				$nft_id_list = "";
				$unsafe = $_POST['nftids'];
				$nftids = trim(addslashes(strip_tags($unsafe)));
				$ids = explode("\n",$nftids);

				foreach($ids as $id)
				{	
					$id = trim($id);
					if(strpos($id,"nft1") >= 0)
					{
						$id = substr($id,strpos($id,"nft1"));
						(strpos($id,",") > 0) ? $id = substr($id,0,strpos($id,",")) : "";
						(strpos($id," ") > 0) ? $id = substr($id,0,strpos($id," ")) : "";
					}
					if(substr($id,0,4) == "nft1")
					{
						$nft_id_list .= $id . "\n";
						$results .= "<div class='nft-table'>";
						$results .= "<table>";
						$api = "https://api.mintgarden.io/nfts/$id";
						$json = file_get_contents($api);
						$json_data = json_decode($json,true);
						$thumbnail = $json_data["data"]["thumbnail_uri"];
						$events = $json_data["events"];
						$events_length = count($events);
						$provenance = "<br><span class='provenancetitle'>Provenance</span><hr>\n";
						for ($x = 0; $x < $events_length; $x++)
						{
							if(strlen($events[$x]["owner"]["name"]) > 0)
							{
								$from = $events[$x]["owner"]["name"];
							} else {
								$from = substr($events[$x]["address"]["encoded_id"],0,8) . "..." . substr($events[$x]["address"]["encoded_id"],58,4);
							}
							$provenance .= "<span class='provenanceitem'>" . get_event_type($events[$x]["type"]) . "<b>" . $from . "</b><br>" . pretty_timestamp($events[$x]["timestamp"]) . "<br></span>\n";
						}
						$results .= "<span class='nft-thumbnail'><img src='" . $thumbnail . "'><br><span class='provenance'>" . $provenance . "</span></span>";
						$results .= "<tr><td>NFT</td><td><img src='assets/img/leaf.png'> " . $json_data["encoded_id"] . "</td></tr>";
						$results .= "<tr><td>&nbsp;</td><td>" . $json_data["data"]["metadata_json"]["name"] . "</td></tr>";
						$results .= "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
						$results .= "<tr><td>Collection</td><td><img src='assets/img/leaf.png'>" . $json_data["collection"]["id"] . "</td></tr>";
						$results .= "<tr><td>&nbsp;</td><td>" . $json_data["data"]["metadata_json"]["collection"]["name"] . "</td></tr>";
						$results .= "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
						(strlen($json_data["creator"]["encoded_id"]) > 0) ? $results .= "<tr><td>Creator</td><td><img src='assets/img/leaf.png'> " . $json_data["creator"]["encoded_id"] . "</td></tr>" : $results .= "<tr><td>Creator</td><td>&nbsp;</td></tr>";
						(strlen($json_data["creator"]["name"]) > 0) ? $results .= "<tr><td>&nbsp;</td><td><img src='assets/img/name2.png'> " . $json_data["creator"]["name"] . "</td></tr>" : "";
						(strlen($json_data["creator"]["bio"]) > 0) ? $results .= "<tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $json_data["creator"]["bio"] . "</td></tr>" : "";
						(strlen($json_data["creator"]["website"]) > 0) ? $results .= "<tr><td>&nbsp;</td><td><img src='assets/img/web.png'> " . $json_data["creator"]["website"] . "</td></tr>" : "";
						(strlen($json_data["creator"]["twitter_handle"]) > 0) ? $results .= "<tr><td>&nbsp;</td><td><img src='assets/img/twitter.png'> <a href='https://twitter.com/" . $json_data["creator"]["twitter_handle"] . "' target='_blank'>@" . $json_data["creator"]["twitter_handle"] . "</a></td></tr>" : "";
						(strlen($json_data["creator_address"]["encoded_id"]) > 0) ? $results .= "<tr><td>&nbsp;</td><td><img src='assets/img/wallet.png'> " . $json_data["creator_address"]["encoded_id"] . "</td></tr>" : "";
						$results .= "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
						(strlen($json_data["owner"]["encoded_id"]) > 0) ? $results .= "<tr><td>Owner</td><td><img src='assets/img/leaf.png'>" . $json_data["owner"]["encoded_id"] . "</td></tr>" : $results .= "<tr><td>Owner</td><td>&nbsp;</td><tr>";
						(strlen($json_data["owner"]["name"]) > 0) ? $results .= "<tr><td>&nbsp;</td><td><img src='assets/img/name2.png'> " . $json_data["owner"]["name"] . "</td></tr>" : "";
						(strlen($json_data["owner"]["bio"]) > 0) ? $results .= "<tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $json_data["owner"]["bio"] . "</td></tr>" : "";
						(strlen($json_data["owner"]["website"]) > 0) ? $results .= "<tr><td>&nbsp;</td><td><img src='assets/img/web.png'> " . $json_data["owner"]["website"] . "</td></tr>" : "";
						(strlen($json_data["owner"]["twitter_handle"]) > 0) ? $results .= "<tr><td>&nbsp;</td><td><img src='assets/img/twitter.png'> <a href='https://twitter.com/" . $json_data["owner"]["twitter_handle"] . "' target='_blank'>@" . $json_data["owner"]["twitter_handle"] . "</a></td></tr>" : "";
						(strlen($json_data["owner_address"]["encoded_id"]) > 0) ? $results .= "<tr><td>&nbsp;</td><td><img src='assets/img/wallet.png'> " . $json_data["owner_address"]["encoded_id"] . "</td></tr>" : "";
						$results .= "</table>";
						$results .= "</div>";
						$results .= "<br><br>";
					}
				}
				(strlen($nft_id_list) > 0) ? $_SESSION["nft_id_list"] = $nft_id_list : "";
				$results .= "<a href='#ownerdetails'>Get Owner Wallet Addresses</a>\n";
				break;
				
			case "collectiondetails":
				$nft_list_from_collection = "";
				$nft_id_list = "";
				$unsafe = $_POST['colid'];
				$col_id = trim(addslashes(strip_tags($unsafe)));
				$api = "https://api.mintgarden.io/collections/$col_id/nfts/ids";
				$json = file_get_contents($api);
				$json_data = json_decode($json,true);
				foreach($json_data as $item)
				{ 
				    $nft_list_from_collection .= $item["encoded_id"] . "," . $item["name"] . "\n";
				    $nft_id_list .= $item["encoded_id"] . "\n";
				}
				(strlen($nft_id_list) > 0) ? $_SESSION["nft_id_list"] = $nft_id_list : "";
				break;

			case "ownerdetails":
				$list_owner_wallets = "";
				$nft_id_list = "";
				$unsafe = $_POST['owner-nftids'];
				$nftids = trim(addslashes(strip_tags($unsafe)));
				$ids = explode("\n",$nftids);

				foreach($ids as $id)
				{	
					$id = trim($id);
					if(strpos($id,"nft1") >= 0)
					{
						$id = substr($id,strpos($id,"nft1"));
						(strpos($id,",") > 0) ? $id = substr($id,0,strpos($id,",")) : "";
						(strpos($id," ") > 0) ? $id = substr($id,0,strpos($id," ")) : "";

					}
					if(substr($id,0,4) == "nft1")
					{
						$nft_id_list .= $id . "\n";
						$api = "https://api.mintgarden.io/nfts/$id";
						
						$json = file_get_contents($api);
						$json_data = json_decode($json,true);
						$list_owner_wallets .= $id . "," . $json_data["owner_address"]["encoded_id"] . "\n";
					}
				}
				(strlen($nft_id_list) > 0) ? $_SESSION["nft_id_list"] = $nft_id_list : "";
				break;
		}
	} 
} 
?>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" id="sideNav">
            <a class="navbar-brand js-scroll-trigger" href="#page-top">
                <span class="d-block d-lg-none">XDNFT Wizard</span>
                <span class="d-none d-lg-block"><a href='https://xdnft.link'><img class="img-fluid img-profile rounded-circle mx-auto mb-2" src="assets/img/profile.png" alt="..." /></a></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav">
	                <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#intro">Intro</a></li>
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#nftdetails">NFT</a></li>
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#collectiondetails">Collection</a></li>
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#ownerdetails">Owner</a></li>
                </ul>
            </div>
   			<div class="social-icons">
				<a class="social-icon" href="https://github.com/steppsr"><i class="fab fa-github"></i></a>
				<a class="social-icon" href="https://twitter.com/steppsr"><i class="fab fa-twitter"></i></a>
			</div>
			<span class='powered-by'>powered by <a href='https://mintgarden.io/' target='_blank'>Mintgarden</a> API <a href='https://api.mintgarden.io/docs' target='_blank'>https://api.mintgarden.io/docs</a>
        </nav>
        <!-- Page Content-->
        <div class="container-fluid p-0">
<?php
		intro();
        nft_details($results);
		collection_details($nft_list_from_collection);
        owner_details($list_owner_wallets);
?>
		<span style='float:right; font-size:0.7rem;'>steppsr@xdnft.link • Copyright &copy; 2023</span>
        </div>
        <script>
		function copyToClipboard(preId) 
		{
		  var preBlock = document.getElementById(preId);
		  var range = document.createRange();
		  range.selectNode(preBlock);
		  window.getSelection().removeAllRanges();
		  window.getSelection().addRange(range);
		  document.execCommand("copy");
		  window.getSelection().removeAllRanges();
		}
		</script>

        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
<?php

function pretty_timestamp($timestamp = "")
{
	// 2022-12-20T13:17:10+00:00
	$tdate = substr($timestamp,0,10);
	$ttime = substr($timestamp,12,7);
	$tmp = $tdate . " " . $ttime;
	return($tmp);
}

function get_event_type($type = "")
{
	switch($type)
	{
		case 0: return "<b>Mint</b> by "; break;
		case 1: return "<b>Transferred</b> to "; break;
		case 2: return "<b>Sale</b> to "; break;
		case 3: return "<b>Burn</b> by "; break;
	}
}

function intro()
{
	echo "<!-- intro -->\n";
	echo "<section class='resume-section' id='intro'>\n";
	echo "<div class='resume-section-content'>\n";
	echo "<h1 class='mb-0'>XDNFT<span class='text-primary'>Wizard</span></h1>\n";
	echo "<div class='subheading mb-5'>NFT Help for creators on the Chia Blockchain.</div>\n";
	echo "<p class='lead mb-5'>Get details on NFTs, Collections, Owners, and Creators.</p>\n";
	echo "</div>\n";
	echo "</section>\n";
	echo "<hr class='m-0' />\n";
}

function nft_details($results = "")
{
	echo "<!-- nftdetails -->\n";
    echo "<section class='resume-section' id='nftdetails'>\n";
	echo "<div class='resume-section-content'>\n";
	echo "<h2 class='mb-1'>NFT Details</h2>\n";
	echo "Get NFT details from NFT IDs.<br><br>\n";
	echo "<div class='d-flex flex-column flex-md-row justify-content-between mb-5'>\n";
	echo "<div class='flex-grow-1'>\n";
	echo "<div class='input-form'>\n";
	echo "<form action='#nftdetails' method='POST'>\n";
	echo "<input type='hidden' id='act' name='act' value='nftdetails'>\n";
	echo "<textarea cols='100' rows='10' id='nftids' name='nftids' placeholder='One NFT ID per line.'></textarea><br>\n";
	echo "<input class='submit-button' name='submit' type='submit' value='Submit'>\n";
	echo "</form>\n";
	echo "<span class='results'>\n";
	if($results != "")
	{
		echo $results;
	}
	echo "</span>\n";
	echo "</div>\n";
	echo "</div>\n";
	echo "</div>\n";
	echo "</div>\n";
	echo "</section>\n";
	echo "<hr class='m-0' />\n";
}

function collection_details($nft_list_from_collection = "")
{
	echo "<!-- collectiondetails -->\n";
	echo "<section class='resume-section' id='collectiondetails'>\n";
	echo "<div class='resume-section-content'>\n";
	echo "<h2 class='mb-1'>Collection Details</h2>\n";
	echo "Get a list of NFT IDs for a collection.<br><br>\n";
	echo "<div class='d-flex flex-column flex-md-row justify-content-between mb-5'>\n";
	echo "<div class='flex-grow-1'>\n";
	echo "<div class='input-form'>\n";
	echo "<form action='#collectiondetails' method='POST'>\n";
	echo "<input type='hidden' id='act' name='act' value='collectiondetails'>\n";
	echo "<input type='text' id='colid' name='colid' placeholder='Collection ID' size='60'></input>\n";
	echo "<input class='button' name='submit' type='submit' value='Submit'>\n";
	echo "</form>\n";
	echo "<br><br><span class='nft_list_from_collection'>\n";
	if($nft_list_from_collection != "")
	{
		$_SESSION["collection-nfts"] = $nft_list_from_collection;
		if(isset($_POST['submit']) && $_POST['submit'] == 'Submit')
		{
			echo "<h2>Collection Details - Results</h2>\n";
			echo "<a href='download.php?cn=collection-nfts' target='_blank'>Download as CSV</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href='#collectiondetails' onclick='copyToClipboard(\"collection-nfts\")'>Copy to Clipboard</a><br><br>\n";
		}
		echo "<code><pre id='collection-nfts'>" . $nft_list_from_collection . "</pre></code>\n";
	}
	echo "</span>\n";
	echo "</div>\n";
	echo "</div>\n";
	echo "</div>\n";
	echo "</div>\n";
	echo "</section>\n";
	echo "<hr class='m-0' />\n";
}

function owner_details($list_owner_wallets = "")
{
	if(isset($_SESSION["nft_id_list"]))
	{
		$nft_ids = $_SESSION["nft_id_list"];
	}
	echo "<!-- ownerdetails -->\n";
	echo "<section class='resume-section' id='ownerdetails'>\n";
	echo "<div class='resume-section-content'>\n";
	echo "<h2 class='mb-1'>Owner Details</h2>\n";
	echo "Get a list of Owner Wallet addresses from NFT IDs.<br><br>\n";
	echo "<div class='d-flex flex-column flex-md-row justify-content-between mb-5'>\n";
	echo "<div class='flex-grow-1'>\n";
	echo "<div class='input-form'>\n";
	echo "<form action='#ownerdetails' method='POST'>\n";
	echo "<input type='hidden' id='act' name='act' value='ownerdetails'>\n";
	echo "<textarea cols='100' rows='10' id='owner-nftids' name='owner-nftids' placeholder='One NFT ID per line.'></textarea><br>\n";
	echo "<input class='submit-button' name='submit' type='submit' value='Submit'>\n";
	echo "</form>\n";
	echo "<span class='results'>\n";
	if($list_owner_wallets != "")
	{
		$_SESSION["owner-wallets"] = $list_owner_wallets;
		if(isset($_POST['submit']) && $_POST['submit'] == 'Submit')
		{
			echo "<h2>Owner Details - Results</h2>\n";
			echo "<a href='download.php?cn=owner-wallets' target='_blank'>Download as CSV</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href='#ownerdetails' onclick='copyToClipboard(\"owner-wallets\")'>Copy to Clipboard</a><br><br>\n";
		}
		echo "<code><pre id='owner-wallets'>" . $list_owner_wallets . "</pre></code>\n";
	}
	echo "</span>\n";
	echo "</div>\n";
	echo "</div>\n";
	echo "</div>\n";
	echo "</div>\n";
	echo "</section>\n";
	echo "<hr class='m-0' />\n";
}

?>
