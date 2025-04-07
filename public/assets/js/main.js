/**
 * Hoofdbestand voor JavaScript functionaliteit
 * 
 * Initialiseert alle JavaScript functionaliteit op de site
 * 
 * @author: Chris van Steenbergen
 */
document.addEventListener('DOMContentLoaded', function() {
    initSollicitatieFormulier();
});

function initSollicitatieFormulier() {
    const solliciteerBtn = document.getElementById('solliciteer-btn');
    const annuleerBtn = document.getElementById('annuleer-btn');
    const formulierSectie = document.getElementById('sollicitatie-formulier');
    const sollicitatieForm = document.getElementById('sollicitatie-form');
    const feedbackDiv = document.getElementById('form-feedback'); // Feedback container
    
    if (!solliciteerBtn || !formulierSectie || !sollicitatieForm || !feedbackDiv) return;
    
    solliciteerBtn.addEventListener('click', function() {
        formulierSectie.style.display = 'block';
        solliciteerBtn.style.display = 'none';
        feedbackDiv.innerHTML = ''; 
        feedbackDiv.className = ''; 
        sollicitatieForm.style.display = 'block'; 
        
        formulierSectie.scrollIntoView({ behavior: 'smooth' });
    });
    
    if (annuleerBtn) {
        annuleerBtn.addEventListener('click', function() {
            formulierSectie.style.display = 'none';
            solliciteerBtn.style.display = 'inline-block';
            
            sollicitatieForm.reset();
            
            resetValidation(sollicitatieForm);
            feedbackDiv.innerHTML = '';
            feedbackDiv.className = '';
        });
    }
    
    sollicitatieForm.addEventListener('submit', function(event) {
        event.preventDefault(); 
        feedbackDiv.innerHTML = ''; 
        feedbackDiv.className = ''; 

        if (typeof validateForm === 'function' && !validateForm(this)) {
            const firstError = this.querySelector('.error');
            if(firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }
        
        feedbackDiv.textContent = 'Bezig met verzenden...';
        feedbackDiv.classList.add('notice'); 

        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true; 

        fetch('/sollicitatie.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                 throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                feedbackDiv.innerHTML = `<p>${data.message}</p>`;
                feedbackDiv.className = 'success-message';
                sollicitatieForm.style.display = 'none';
                sollicitatieForm.reset(); 
            } else {
                feedbackDiv.innerHTML = `<p>${data.message}</p>`; 
                feedbackDiv.className = 'error-message'; 
                feedbackDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        })
        .catch(error => {
            console.error('Fout bij verzenden formulier:', error);
            feedbackDiv.innerHTML = '<p>Er is een onverwachte fout opgetreden bij het verzenden. Controleer je internetverbinding en probeer het opnieuw.</p>';
            feedbackDiv.className = 'error-message'; 
        })
        .finally(() => {
            submitButton.disabled = false;
            if (feedbackDiv.classList.contains('success-message') || feedbackDiv.classList.contains('error-message')) {
                feedbackDiv.classList.remove('notice');
            } else {
                feedbackDiv.textContent = ''; 
            }
        });
    });
} 