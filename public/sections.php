<?php

function SectionOne() { 
echo '<div class="SectionOne">';
echo '<div class="row">';
echo '<div class="column">';
deviceinfo();
tempbutton();
echo '</div>';
echo '<div class="column">';
updatebutton();
rebootbutton();
echo '</div>';
echo '<div class="column">';
startbutton();
stopbutton();
echo '</div></div></div>';
}
function SectionTwo() {
echo '<div class="SectionTwo">';
echo '<div class="Table">';
?>
<?php
newtable();
echo '</div></div>';
}

function SectionThree() { 
echo '<div class="SectionThree">';
//echo 'Placeholder';
echo '</div>';
}

function SectionFour() {
//echo 'Placeholder';
}

?>
