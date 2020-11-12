function showHideStuff($typeSelector, $toggleArea, $selectStatus) {
	if ($typeSelector.val() === $selectStatus) {
		$toggleArea.show(); 
	} else {
		$toggleArea.hide(); 
	}
    $typeSelector.change(function(){
        if ($typeSelector.val() === $selectStatus) {
            $toggleArea.show(300); 
        }
        else {
            $toggleArea.hide(300); 
        }
    });
}