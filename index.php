<html>
	<head>
		<meta charset="utf8">
		<title>Kitsu Wordpress testint page</title>
	</head>

	<body>
		<h1>Welcome to Kitsu Wordpress Shortcode testing page</h1>
		<form method="POST" action="">
			<input type="text" placeholder="Kitsu URI" name="uri" />
			<button type="submit">Get!</button>
		</form>

		<?php
			$uri = $_POST['uri'];

			$search = explode("/", $uri);

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Accept: application/vnd.api+json',
				'Content-Type: application/vnd.api+json'));
			curl_setopt($curl, CURLOPT_URL, 'https://kitsu.io/api/edge/' . $search[3] . '?filter[slug]=' . $search[4]);

			$data = curl_exec($curl);

			$resArr = array();
			$resArr = json_decode($data, true);

			$attributes = $resArr['data'][0]['attributes'];
			$type       = $resArr['data'][0]['type'];

			$image_uri = $attributes['posterImage']['tiny'];
			$title     = $attributes['titles']['en'];
			$slug      = $attributes['slug'];

			function uriBuilder($type, $slug) {
				return "https://kitsu.io/$type/$slug";
			}
		?>

		<style>
			@import url('https://fonts.googleapis.com/css?family=Asap');

			#kitsu_block {
				position: relative;
				top: 100px;
				background: #f75239;
				width: 30%;
				padding: 0.5em;
				font-family: Asap, sans-serif;
				border-radius: 2px;
				color: whitesmoke;
			}

			#kitsu_block img {
				position: relative;
				bottom: 80px;
				border-radius: 2px;
			}

			#kitsu_block a {
				text-decoration: none;
				padding: .8em;
				background-color: #16a085;
				border-radius: 5px;
				color: inherit;
				position: relative;
				bottom: 10px;
			}

			#kitsu_block h2 {
				position: relative;
				bottom: 1em;
			}
		</style>

		<div id="kitsu_block">
			<img src="<?php echo $image_uri; ?>" align="left" style="margin-right: 1em;" />
			<h2><?php echo $title; ?></h2>
			<a href="<?php echo uriBuilder($type, $slug);?>">
				View on Kitsu
			</a>
		</div>
	</body>
</html>
