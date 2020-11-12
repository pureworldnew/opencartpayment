$('.options').find('select').on('change',function(){
    GroupProduct.run=0;
    GroupProduct.changeOption($(this));
});

$("#get-unit-data").change(function(){
    GroupProduct.addUnit();
    GroupProduct.updatePrice();
});

function checkit(selectObj)
{ 
    GroupProduct.run=0;
    GroupProduct.changeOption($(selectObj));
}