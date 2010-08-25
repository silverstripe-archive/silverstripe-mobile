<div id="Sidebar" class="typography">
	<h6><% control Level(1) %>$Title<% end_control %></h6>
	<ul id="Menu">
	  	<% control Menu(2) %>
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
								<% control Children %>
									<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode levelb">$MenuTitle.XML</a></li>
								<% end_control %>
							</ul>
						</li>
					</ul>
		 		 <% end_if %>
			<% end_if %> 
		</li> 
		<% end_control %>
	</ul>
</div>
  