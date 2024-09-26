window.capitalizeFirstLetter = function(word){
    return word.charAt(0).toUpperCase() + word.slice(1);
};

window.currentDate = function(){
    const now = new Date();

    const day = String(now.getDate()).padStart(2, '0');
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const year = now.getFullYear();

    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    const formattedDateTime = `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
    return formattedDateTime;
}

window.formatDate = function(dateString){
    let date = new Date(dateString);
    let options = { weekday: 'long' };
    let dayOfWeek = date.toLocaleDateString('us-US', options);
    const dateParts = dateString.split('-');
    
    return `${dayOfWeek} <br> ${dateParts[1]}-${dateParts[2]}`;
}

window.langMonths = function(month, lang){
    // Month names in different languages
    const months = {
        "ar": {
            "January": "يناير",
            "February": "فبراير",
            "March": "مارس",
            "April": "أبريل",
            "May": "ماي",
            "June": "يونيو",
            "July": "يوليوز",
            "August": "غشت",
            "September": "شتنبر",
            "October": "أكتوبر",
            "November": "نونبر",
            "December": "ديسمبر"
        },
        "fr": {
            "January": "Janvier",
            "February": "Février",
            "March": "Mars",
            "April": "Avril",
            "May": "Mai",
            "June": "Juin",
            "July": "Juillet",
            "August": "Août",
            "September": "Septembre",
            "October": "Octobre",
            "November": "Novembre",
            "December": "Décembre"
        }
    };

    // Fallback messages
    const fallback = {
        "ar": 'مجهول',
        "fr": 'Inconnue'
    };

    // Check if the selected language exists
    if (!months[lang]){
        return fallback[lang] || 'Unknown';
    }

    // Handle month/year format
    if (month.includes('/')){
        const [monthName, year] = month.split('/');
        return months[lang][monthName] ? `${months[lang][monthName]}/${year}` : fallback[lang];
    }

    // Return translated month or fallback message
    return months[lang][month] || fallback[lang];
}
