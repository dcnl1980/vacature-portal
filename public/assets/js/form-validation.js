/**
 * Valideert het sollicitatieformulier
 * 
 * @param {HTMLFormElement} form Het te valideren formulier 
 * @returns {boolean} True als het formulier geldig is, anders false
 * 
 * @author: Chris van Steenbergen
 */
function validateForm(form) {
    resetValidation(form);
    
    let isValid = true;
    
    const naamInput = form.elements.naam;
    if (!naamInput.value.trim() || naamInput.value.trim().length < 2) {
        showError(naamInput, 'Voer een geldige naam in (minimaal 2 tekens)');
        isValid = false;
    }
    
    const emailInput = form.elements.email;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailInput.value.trim() || !emailRegex.test(emailInput.value.trim())) {
        showError(emailInput, 'Voer een geldig e-mailadres in');
        isValid = false;
    }
    
    const cvInput = form.elements.cv;
    if (cvInput.files.length === 0) {
        showError(cvInput, 'Selecteer een CV bestand');
        isValid = false;
    } else {
        const file = cvInput.files[0];
        const fileType = file.type;
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        
        if (!allowedTypes.includes(fileType)) {
            showError(cvInput, 'Gebruik een geldig bestandsformaat (PDF of Word)');
            isValid = false;
        }
        
        const maxSize = 2 * 1024 * 1024; // 2MB
        if (file.size > maxSize) {
            showError(cvInput, 'Bestandsgrootte mag maximaal 2MB zijn');
            isValid = false;
        }
    }
    
    const motivatieInput = form.elements.motivatie;
    if (!motivatieInput.value.trim() || motivatieInput.value.trim().length < 50) {
        showError(motivatieInput, 'Voer een motivatie in van minimaal 50 tekens');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Toont een foutmelding bij een formulierveld
 * 
 * @param {HTMLElement} inputElement Het formulierveld met de fout
 * @param {string} message De foutmelding die getoond moet worden
 * 
 * @author: Chris van Steenbergen
 */
function showError(inputElement, message) {
    inputElement.classList.add('error');
    
    const errorId = inputElement.id + '-error';
    let errorElement = document.getElementById(errorId);
    
    if (errorElement) {
        errorElement.textContent = message;
    }
}

/**
 * Reset alle validatiefouten in een formulier
 * 
 * @param {HTMLFormElement} form Het formulier waarvan de validatie gereset moet worden
 * 
 * @author: Chris van Steenbergen
 */
function resetValidation(form) {
    const errorFields = form.querySelectorAll('.error');
    errorFields.forEach(field => field.classList.remove('error'));
    
    const errorMessages = form.querySelectorAll('.error-message');
    errorMessages.forEach(message => message.textContent = '');
} 