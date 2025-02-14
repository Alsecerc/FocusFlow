document.addEventListener('DOMContentLoaded', function() {
    const formSteps = document.querySelectorAll('.form-step');
    const nextButtons = document.querySelectorAll('.next-btn');
    const progressSteps = document.querySelectorAll('.step');
    let currentStep = 0;

    // Function to update form steps
    function updateFormSteps() {
        formSteps.forEach((step, index) => {
            step.classList.remove('active');
            if (index === currentStep) {
                step.classList.add('active');
            }
        });

        // Update progress bar steps
        progressSteps.forEach((step, index) => {
            step.classList.remove('active');
            if (index <= currentStep) {
                step.classList.add('active');
            }
        });
    }

    // Handle next button clicks
    nextButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Validate current step inputs
            const currentFormStep = formSteps[currentStep];
            const inputs = currentFormStep.querySelectorAll('input, select');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value) {
                    isValid = false;
                    input.style.borderBottom = '1px solid red';
                } else {
                    input.style.borderBottom = '1px solid #ccc';
                }
            });

            if (isValid && currentStep < formSteps.length - 1) {
                currentStep++;
                updateFormSteps();
            }
        });
    });

    // Form submission
    const form = document.getElementById('signupForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        // Add your form submission logic here
        console.log('Form submitted');
    });
});

class ProgressBar {
    constructor() {
        this.currentStep = 1;
        this.steps = document.querySelectorAll('.step');
        this.lines = document.querySelectorAll('.progress-bar-line');
        this.formSteps = document.querySelectorAll('.form-step');
        this.nextButtons = document.querySelectorAll('button');
        
        this.init();
    }
    
    init() {
        // Add click listeners to next buttons
        this.nextButtons.forEach((button, index) => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                if (this.validateStep(index)) {
                    this.goToNextStep();
                }
            });
        });
    }
    
    validateStep(stepIndex) {
        const inputs = this.formSteps[stepIndex].querySelectorAll('input');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                this.showError(input);
            } else {
                this.removeError(input);
            }
        });

        return isValid;
    }

    showError(input) {
        input.style.borderColor = 'red';
    }

    removeError(input) {
        input.style.borderColor = '#ccc';
    }
    
    goToNextStep() {
        if (this.currentStep >= this.steps.length) return;
        
        // Update steps
        this.steps[this.currentStep].classList.add('active');
        if (this.currentStep > 0) {
            this.lines[this.currentStep - 1].classList.add('active');
        }
        
        // Update form visibility
        this.formSteps[this.currentStep - 1].style.display = 'none';
        this.formSteps[this.currentStep].style.display = 'block';
        
        this.currentStep++;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const progressBar = new ProgressBar();
});