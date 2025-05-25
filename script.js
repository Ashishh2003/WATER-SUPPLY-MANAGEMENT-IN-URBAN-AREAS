// public/script.js
document.getElementById('registerForm')?.addEventListener('submit', function (e) {
    e.preventDefault();

    // Capture form data
    const name = document.getElementById('name').value;
    const can = document.getElementById('can').value;
    const address = document.getElementById('address').value;
    const phone = document.getElementById('phone').value;
    const password = document.getElementById('password').value;

    // Store user details in localStorage
    const user = { name, can, address, phone, password };
    localStorage.setItem('user', JSON.stringify(user));

    alert('Registration successful! You can now login.');
    window.location.href = 'login.html';
});

document.getElementById('loginForm')?.addEventListener('submit', function (e) {
    e.preventDefault();

    const phone = document.getElementById('phone').value;
    const password = document.getElementById('password').value;

    // Retrieve user data from localStorage
    const storedUser = JSON.parse(localStorage.getItem('user'));

    // Validate login credentials
    if (storedUser && storedUser.phone === phone && storedUser.password === password) {
        alert('Login successful!');
        // Redirect to dashboard or homepage (not implemented here)
        window.location.href = 'home.html'
    } else {
        alert('Invalid phone number or password.');
    }
});

// public/script.js

document.getElementById('drainageForm')?.addEventListener('submit', function (e) {
    e.preventDefault();

    const image = document.getElementById('image').files[0];
    const landmark = document.getElementById('landmark').value;

    console.log('Drainage Complaint Submitted:', { image, landmark });

    alert('Complaint submitted successfully!');
    // Redirect to dashboard after submission
    window.location.href = 'home.html';
});

// Function to perform AI-powered smart search
function smartSearch(query) {
    // Placeholder for AI search logic
    // This could be an API call to a search service
    // For now, we'll simulate with a simple keyword check
    const results = [];
    const data = ["water", "sewerage", "complaint", "admin", "dashboard"];

    data.forEach(item => {
        if (item.includes(query.toLowerCase())) {
            results.push(item);
        }
    });

    return results;
}

// Function to personalize user experience
function personalizeContent(userPreferences) {
    // Placeholder for AI personalization logic
    // This could be an API call to a personalization service
    // For now, we'll simulate with a simple preference check
    const content = {
        theme: userPreferences.theme || "light",
        language: userPreferences.language || "en"
    };

    return content;
}

// Example usage
const searchResults = smartSearch("water");
console.log("Search Results:", searchResults);

const userContent = personalizeContent({theme: "dark", language: "fr"});
console.log("Personalized Content:", userContent);
