<ul>
	<% control Menu(1) %>
		<% if Children %>
	  	    <li class="$LinkingMode"><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode levela">$MenuTitle.XML</a>
		<% else %>
			<li class="$LinkingMode levela"><a href="$Link" title="Go to the $Title.XML page" >$MenuTitle.XML</a>
		<% end_if %>	  
		<% if LinkOrSection = section %>
			<% if Children %>
				<ul class="sub">
					<% control Children %>
					<li class="$LinkingMode levelb"><a href="$Link" title="Go to the $Title.XML page">$MenuTitle.XML</a></li>
				<% end_control %>
				</ul>
	 		 <% end_if %>
		<% end_if %> 
	</li> 
	<% end_control %>
</ul>