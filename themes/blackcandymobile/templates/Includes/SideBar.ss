<div id="Sidebar" class="typography">
	<h6><% loop Level(1) %>$Title<% end_loop %></h6>
	<ul id="Menu">
	  	<% loop Menu(2) %>
 	    		<% if Children %>
		  	    <li class="$LinkingMode"><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode levela">$MenuTitle.XML</a>
  	    	<% else %>
	  			<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode levela">$MenuTitle.XML</a>
			<% end_if %>	  
  		
  			<% if LinkOrSection = section %>
  				<% if Children %>
					<ul class="sub">
						<li>
							<ul class="roundWhite">
								<% loop Children %>
									<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode levelb">$MenuTitle.XML</a></li>
								<% end_loop %>
							</ul>
						</li>
					</ul>
		 		 <% end_if %>
			<% end_if %> 
		</li> 
		<% end_loop %>
	</ul>
</div>
  