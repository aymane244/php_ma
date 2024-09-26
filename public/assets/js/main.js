$(document).ready(function(){
    $(".btn-copy").click(async function(){
        let code = $(this).data('code');
        let textToCopy = code;
        let textElement = $(".text");

        try{
            await navigator.clipboard.writeText(textToCopy);
    
            textElement.fadeIn(700);
            setTimeout(function(){
                textElement.fadeOut(700);
            }, 2000);
        } catch (err) {
            console.error('Text konnte nicht kopiert werden: ', err);
        }
    });

    $("#show_less").click(function(){
        $("#text-less").toggleClass('truncate');
        $("#text-less").hasClass('truncate') ? $("#show_less").html("Mehr anzeigen") : $("#show_less").html("Weniger anzeigen")
    });

    $("#enable").on('change', function(){
        $("#enable").is(':checked') ? $('#text-enable').text("Aktivieren") : $('#text-enable').text("Deaktivieren")
    });

    $('.cookie').addClass('slide-in-top');

    const updateCookieBanner = () =>{
        const cookies = document.cookie.split(";").map(cookie => cookie.trim().split("="));
        const cookie = cookies.find(([name]) => name === "cookiePreferences");

        if(cookie){
            const preferences = JSON.parse(decodeURIComponent(cookie[1]));
            if(preferences.nonEssential){
                // Example: Initialize non-essential cookies or features
                console.log("Non-essential cookies are accepted.");
                // Initialize tracking or other scripts here
            }
            $("#cookie").hide();
        }else{
            $("#cookie").show();
        }
    };

    $("#accept_cookie").click(function(){
        const nonEssential = $("#enable").is(":checked");
        const preferences = {
            nonEssential: nonEssential
        };

        const d = new Date();
        d.setMonth(d.getMonth() + 1);
        document.cookie = 'cookiePreferences=' + encodeURIComponent(JSON.stringify(preferences)) + ';expires=' + d.toUTCString() + ';path=/';
        updateCookieBanner();
    });

    $("#close_cookie").click(function(){
        $("#cookie_modal").modal('show');
    });

    // Initially check if the banner needs to be shown
    updateCookieBanner();
    
    setTimeout(() => {
        updateCookieBanner();
    }, 0);
});