class Carousel {
    constructor() {
        this.currentSlide = 0;
        this.slides = document.querySelectorAll('.carousel-slide');
        this.dots = document.querySelectorAll('.dot');
        this.totalSlides = this.slides.length;
        this.autoplayInterval = null;

        this.prevBtn = document.querySelector('.carousel-nav.prev');
        this.nextBtn = document.querySelector('.carousel-nav.next');

        this.init();
    }

    init() {
        this.showSlide(0);

        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                this.currentSlide = index;
                this.showSlide(index);
                this.resetAutoplay();
            });
        });

        this.prevBtn.addEventListener('click', () => {
            this.prevSlide();
            this.resetAutoplay();
        });
        this.nextBtn.addEventListener('click', () => {
            this.nextSlide();
            this.resetAutoplay(); 
        });

        this.startAutoplay();

        const container = document.querySelector('.carousel-container');
        container.addEventListener('mouseenter', () => this.stopAutoplay());
        container.addEventListener('mouseleave', () => this.startAutoplay());
    }

    showSlide(index) {
        this.slides.forEach(slide => slide.classList.remove('active'));
        this.dots.forEach(dot => dot.classList.remove('active'));

        this.slides[index].classList.add('active');
        this.dots[index].classList.add('active');
    }

    prevSlide() {
        this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
        this.showSlide(this.currentSlide);
    }

    nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
        this.showSlide(this.currentSlide);
    }

    startAutoplay() {
        this.autoplayInterval = setInterval(() => {
            this.nextSlide();
        }, 5000);
    }

    stopAutoplay() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
            this.autoplayInterval = null;
        }
    }

    resetAutoplay() {
        this.stopAutoplay();
        this.startAutoplay();
    }
}
class FormValidator {
    constructor() {
        this.currentStep = 0;
        this.steps = document.querySelectorAll('.step');
        this.lines = document.querySelectorAll('.progress-bar-line');
        this.formSteps = document.querySelectorAll('.form-step');
        this.nextButtons = document.querySelectorAll('.next-btn');
        this.form = document.getElementById('signupForm');
        this.init();
    }

    init() {
        this.showStep(this.currentStep);
        this.setupInputMasks();
        this.setupValidation();
        this.setupNavigation();
    }

    setupInputMasks() {
        const timeInput = document.getElementById('preferred_hours');
        timeInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/[^0-9\-apmAMP\s:]/g, '');
            const times = value.split('-').map(t => t.trim());
            if (times.length <= 2) {
                e.target.value = value;
            }
        });
    }

    setupValidation() {
        const passwordInput = document.getElementById('password');
        passwordInput.addEventListener('input', () => this.validatePassword(passwordInput));

        const confirmPasswordInput = document.querySelector('input[name="confirmPassword"]');

        confirmPasswordInput.addEventListener('input', () => {
            this.validateConfirmPassword(passwordInput, confirmPasswordInput);
        });
        
        passwordInput.addEventListener('input', () => {
            if (confirmPasswordInput.value) {
                this.validateConfirmPassword(passwordInput, confirmPasswordInput);
            }
        });

        const emailInput = document.getElementById('email');
        emailInput.addEventListener('input', () => this.validateEmail(emailInput));

        const ageInput = document.getElementById('age');
        ageInput.addEventListener('input', () => this.validateAge(ageInput));

        const timeInput = document.getElementById('preferred_hours');
        timeInput.addEventListener('blur', () => this.validateTime(timeInput));

        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            if (this.validateAllFields()) {
                this.form.submit();
            }

            let formData = new FormData(this);
            fetch('LoginBackend.php',{
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log('Server response:', data);
                // Optionally, handle the server response here
            })
            .catch(error => console.error('Error:', error));
        });
    }

    setupNavigation() {
        this.nextButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                if (this.validateStep(this.currentStep)) {
                    this.goToNextStep();
                }
            });
        });
    }

    showStep(stepIndex) {
        this.formSteps.forEach((step, index) => {
            step.classList.toggle('active', index === stepIndex);
        });
        this.updateProgress();
    }

    validatePassword(input) {
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

    validateConfirmPassword(passwordInput, confirmPasswordInput) {
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

    validateEmail(input) {
        const value = input.value;
        const errorDiv = document.getElementById('emailError');
        const emailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

        if (!emailRegex.test(value)) {
            errorDiv.textContent = "Please enter a valid Gmail address";
            errorDiv.style.display = 'block';
            input.classList.add('invalid');
            return false;
        }

        errorDiv.style.display = 'none';
        input.classList.remove('invalid');
        return true;
    }

    validateAge(input) {
        const value = parseInt(input.value);
        const errorDiv = document.getElementById('ageError');

        if (isNaN(value) || value < 16 || value > 100) {
            errorDiv.textContent = "Please enter a valid age (16-100)";
            errorDiv.style.display = 'block';
            input.classList.add('invalid');
            return false;   
        }

        errorDiv.style.display = 'none';
        input.classList.remove('invalid');
        return true;
    }

    validateTime(input) {
        const value = input.value;
        const errorDiv = document.getElementById('hoursError');
        const timePattern = /^(1[0-2]|0?[1-9])(:[0-5][0-9])?\s*(am|pm)\s*-\s*(1[0-2]|0?[1-9])(:[0-5][0-9])?\s*(am|pm)$/i;

        if (!timePattern.test(value)) {
            errorDiv.textContent = "Please enter time in format: '9am - 5pm'";
            errorDiv.style.display = 'block';
            input.classList.add('invalid');
            return false;
        }

        const [startTime, endTime] = value.split('-').map(t => this.convertTo24Hour(t.trim()));
        if (startTime >= endTime) {
            errorDiv.textContent = "End time must be later than start time";
            errorDiv.style.display = 'block';
            input.classList.add('invalid');
            return false;
        }

        errorDiv.style.display = 'none';
        input.classList.remove('invalid');
        return true;
    }

    convertTo24Hour(time12h) {
        const [time, modifier] = time12h.toLowerCase().split(/\s*(am|pm)\s*/);
        let [hours, minutes] = time.split(':').map(Number);
        
        if (minutes === undefined) minutes = 0;
        
        if (hours === 12) {
            hours = modifier === 'pm' ? 12 : 0;
        } else if (modifier === 'pm') {
            hours = hours + 12;
        }
        
        return hours + minutes/60;
    }

    validateStep(stepIndex) {
        const currentStep = this.formSteps[stepIndex];
        let isValid = true;

        if (stepIndex === 0) {
            const password = document.getElementById('password');
            const email = document.getElementById('email');
            
            if (!this.validatePassword(password)) isValid = false;
            if (!this.validateEmail(email)) isValid = false;
        } else if (stepIndex === 1) {
            const age = document.getElementById('age');
            const gender = document.querySelector('input[name="gender"]:checked');
            const genderError = document.getElementById('genderError');

            if (!this.validateAge(age)) isValid = false;
            if (!gender) {
                genderError.textContent = "Please select a gender";
                genderError.style.display = 'block';
                isValid = false;
            } else {
                genderError.style.display = 'none';
            }
        } else if (stepIndex === 2) {
            const time = document.getElementById('preferred_hours');
            if (!this.validateTime(time)) isValid = false;
        }

        return isValid;
    }

    validateAllFields() {
        let isValid = true;
        for (let i = 0; i < this.formSteps.length; i++) {
            if (!this.validateStep(i)) {
                isValid = false;
            }
        }
        return isValid;
    }

    goToNextStep() {
        if (this.currentStep < this.formSteps.length - 1) {
            this.currentStep++;
            this.showStep(this.currentStep);
        }
    }

    updateProgress() {
        this.steps.forEach((step, index) => {
            step.classList.toggle('active', index <= this.currentStep);
        });

        this.lines.forEach((line, index) => {
            line.classList.toggle('active', index < this.currentStep);
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const carousel = new Carousel();
    const formValidator = new FormValidator();
});