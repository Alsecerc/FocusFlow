
function validatePassword(input) {
    const value = input.value;
    const errorDiv = document.getElementById('passwordError');
    const hasLength = value.length >= 8 && value.length <= 10;
    const hasLetter = /[a-zA-Z]/.test(value);
    const hasNumber = /[0-9]/.test(value);
    const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(value);

    let errorMessage = [];
    if (!hasLength) errorMessage.push("Password must be 8-10 characters");
    if (!hasLetter) errorMessage.push("Include at least one letter");
    if (!hasNumber) errorMessage.push("Include at least one number");
    if (!hasSymbol) errorMessage.push("Include at least one symbol");

    if (errorMessage.length > 0) {
        errorDiv.textContent = errorMessage.join(', ');
        errorDiv.style.display = 'block';
        input.classList.add('invalid');
        return false;
    }

    errorDiv.style.display = 'none';
    input.classList.remove('invalid');
    return true;
}

function validateConfirmPassword(passwordInput, confirmPasswordInput) {
    const errorDiv = document.getElementById('passwordconfirmError');
    
    if (confirmPasswordInput.value !== passwordInput.value) {
        errorDiv.textContent = "Passwords do not match";
        errorDiv.style.display = 'block';
        confirmPasswordInput.classList.add('invalid');
        return false;
    }
    
    errorDiv.style.display = 'none';
    confirmPasswordInput.classList.remove('invalid');
    return true;
}

function validateEmail(input) {
    const value = input.value;
    const errorDiv = document.getElementById('emailError');
    const emailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

    if (!emailRegex.test(value)) {
        errorDiv.textContent = "Please enter a valid Gmail address";
        errorDiv.style.display = 'block';
        return false;
    }

    errorDiv.style.display = 'none';
    return true;
}

function ValidInput (e){
    let form = e.target.closest('form');
    if (!form) {
        console.error('No form found');
        return false;
    }
    
    let isValid = true;

    let email = Array.from(form.elements).find(input => input.id === 'email');
    let password = Array.from(form.elements).find(input => input.id === 'password');
    let confirmPassword = Array.from(form.elements).find(input => input.id === 'confirmPassword');
    
    console.log(email.value, password.value, confirmPassword.value);
    if (!validateEmail(email)) isValid = false;
    if (!validatePassword(password)) isValid = false;
    if (!validateConfirmPassword(password, confirmPassword)) isValid = false;
    return isValid;
}

function PassValue(){
    document.addEventListener('submit', (e)=>{
        e.preventDefault();
        if (ValidInput(e)){
            let formData = new FormData(e.target);
            fetch('ForgotPasswordBackend.php', {
                method: 'POST',
                body: formData
            }).then((response)=>{
                return response.json();
            }).then((data)=>{
                console.log(data.status);
                if (data.status === 'success') {
                    alert(data.message);
                    window.location.href = '../Login.php';
                    clearForm();
                } else if (data.status === 'error') {
                    alert(data.message);
                    clearForm();
                }
            }).catch((error)=>{
                console.log(error);
            })
        }else{
            console.log('Invalid input');
        }
    })
    
}

function clearForm(){
    document.getElementById('email').value = '';
    document.getElementById('password').value = '';
    document.getElementById('confirmPassword').value = '';
}

PassValue();