<script src="https://www.google.com/jsapi" type="text/javascript"></script>
<script language="Javascript" type="text/javascript">
google.load("search", "1");

function OnLoad() {
// Create a search control
var searchControl = new google.search.SearchControl();

// Add in a full set of searchers
//var localSearch = new google.search.LocalSearch();
//searchControl.addSearcher(localSearch);
searchControl.addSearcher(new google.search.WebSearch());
//searchControl.addSearcher(new google.search.VideoSearch());
//searchControl.addSearcher(new google.search.BlogSearch());
//searchControl.addSearcher(new google.search.NewsSearch());
//searchControl.addSearcher(new google.search.ImageSearch());
//searchControl.addSearcher(new google.search.BookSearch());
//searchControl.addSearcher(new google.search.PatentSearch());

// Set the Local Search center point
//localSearch.setCenterPoint("New York, NY");

// tell the searcher to draw itself and tell it where to attach
searchControl.draw(document.getElementById("searchcontrol"));

// execute an inital search
searchControl.execute("");
}
google.setOnLoadCallback(OnLoad);
</script>
<div id="searchcontrol" />