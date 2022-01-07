$(document).ready(function(){

// Editor for post content CKEDITOR
ClassicEditor
.create( document.querySelector( '#body' ) )
.catch( error => {
    console.error( error );
} );



$('#selectAllBoxes').click(function(event){

if(this.checked){
    $('.checkBoxes').each(function(){
        this.checked = true;

    });
    
}else{
    
        $('.checkBoxes').each(function(){
            this.checked = false;
    
        });
}
    

});


// LOADING FUNCTION ON REFRESH
// var div_box = "<div id='load-screen'><div id='loading'></div></div>";



// $("body").prepend(div_box);

// $('#load-screen').delay(2000).fadeOut(600, function(){
//     $(this).remove();

// });

});


function loadUsersOnline() {

    $.get("functions.php?onlineusers=result", function(data)
     {
         $(".usersonline").text(data);

    });
}

setInterval(function() {

    loadUsersOnline();
}, 500);

loadUsersOnline();