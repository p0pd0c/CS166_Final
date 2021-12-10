// Wait for document to load
$(document).ready(function() {
    console.log("READY")
    // Add event listener to the generate password button
    $("#generatePassword").click(function() {
        // remove the button
        $(this).slideUp();

        // Generate password based on guidelines
        let newpassword = ""
        let specialChars = "!@#$^&*()-+,"
        specialChars = specialChars.split("")
        let loweralphahet = "abcdefghijklmnopqrstuvwxyz"
        loweralphahet = loweralphahet.split("")
        let upperalphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
        upperalphabet = upperalphabet.split("")
        let numbers = "0123456789"
        numbers = numbers.split("")
        // Build password
        for(let i = 0; i < 25; i++) {
            // Pick a random letter
            let randomIndex = randomNum(0, loweralphahet.length - 1)
            // Alternate between lower- and upper- case
            if(i % 2 == 0) {
                newpassword += loweralphahet[randomIndex]
            } else {
                newpassword += upperalphabet[randomIndex]
            }
        }
        // Add a number randomly
        let randomIndex = randomNum(0, newpassword.length - 1)
        let randomNumberIndex = randomNum(0, numbers.length - 1)
        newpassword = newpassword.split("")
        newpassword[randomIndex] = numbers[randomNumberIndex]
        // Add a special char randomly where the number isn't
        let randomSpecialIndex = randomNum(0, specialChars.length - 1)
        let randomNewIndex = randomNum(0, newpassword.length - 1)
        while(randomNewIndex == randomIndex) {
            randomNewIndex = randomNum(0, newpassword.length - 1)
        }
        newpassword[randomNewIndex] = specialChars[randomSpecialIndex]
        newpassword = newpassword.join("")

        // Append the password to the document 
        $(this).parent().append(`<p>${newpassword}</p>`);
    })
})

// Helper function
// Get a random num in the range [min, max]
function randomNum(min, max) {
	return Math.floor(Math.random() * (max - min)) + min; // You can remove the Math.floor if you don't want it to be an integer
}
