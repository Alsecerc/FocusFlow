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