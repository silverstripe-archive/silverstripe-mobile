<!DOCTYPE html>
<html lang="en">
  <head>
		<% base_tag %>
		<title><% if MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> &raquo; $SiteConfig.Title</title>
		$MetaTags(false)
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
		<link rel="shortcut icon" href="/favicon.ico" />
		<% require themedCSS(layout) %>
		<% require themedCSS(typography) %>
		<% require themedCSS(form) %>
		<script type="text/javascript" src="$ThemeDir/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="$ThemeDir/js/jquery.iphone.min.js"></script>
		<script type="text/javascript" src="$ThemeDir/js/iphone.js"></script>
	</head>
	<body>
		<div id="Container">
			<div id="Header">
				<h1>$SiteConfig.Title</h1>
				<p>$SiteConfig.Tagline</p>
			</div>

			<div id="Navigation">
				<% include Navigation %>
			</div>

			<div id="Layout">
				$Layout
			</div>

			<div id="Footer">
				<% include Footer %>
			</div>
		</div>
	</body>
</html>