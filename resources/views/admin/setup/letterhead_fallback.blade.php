 <style>
     :root {
         --primary-color: #750096;
         --primary-dark: #CB6CE6;
         --secondary-color: #6c757d;
         --border-color: #dee2e6;
         --bg-light: #f8f9fa;
         --text-dark: #212529;
         --text-muted: #6c757d;
     }

     body {
         background: #f5f7fa;
         font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
         padding: 2rem;
     }

     /* Empty State Container */
     .empty-state-container {
         max-width: 600px;
         margin: 4rem auto;
         text-align: center;
     }

     .empty-state-card {
         background: white;
         border-radius: 16px;
         padding: 3rem 2rem;
         box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
         border: 1px solid var(--border-color);
     }

     /* Icon Container */
     .empty-state-icon {
         width: 120px;
         height: 120px;
         margin: 0 auto 2rem;
         background: linear-gradient(135deg, #75009673 0%, #CB6CE673 100%);
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         position: relative;
         animation: pulse 2s ease-in-out infinite;
     }

     @keyframes pulse {

         0%,
         100% {
             transform: scale(1);
         }

         50% {
             transform: scale(1.05);
         }
     }

     .empty-state-icon::before {
         content: '';
         position: absolute;
         inset: -10px;
         border-radius: 50%;
         border: 2px dashed #750096;
         opacity: 0.3;
         animation: rotate 20s linear infinite;
     }

     @keyframes rotate {
         from {
             transform: rotate(0deg);
         }

         to {
             transform: rotate(360deg);
         }
     }

     .empty-state-icon i {
         font-size: 3.5rem;
         color: #750096;
     }

     /* Text Content */
     .empty-state-title {
         font-size: 1.75rem;
         font-weight: 700;
         color: var(--text-dark);
         margin-bottom: 1rem;
     }

     .empty-state-description {
         font-size: 1rem;
         color: var(--text-muted);
         line-height: 1.6;
         margin-bottom: 2rem;
         max-width: 450px;
         margin-left: auto;
         margin-right: auto;
     }

     /* Steps List */
     .steps-list {
         text-align: left;
         max-width: 400px;
         margin: 2rem auto;
         padding: 1.5rem;
         background: var(--bg-light);
         border-radius: 12px;
         border: 1px solid var(--border-color);
     }

     .steps-title {
         font-size: 0.875rem;
         font-weight: 700;
         color: var(--primary-color);
         text-transform: uppercase;
         letter-spacing: 1px;
         margin-bottom: 1rem;
         text-align: center;
     }

     .step-item {
         display: flex;
         align-items: flex-start;
         gap: 1rem;
         margin-bottom: 1rem;
         padding: 0.75rem;
         background: white;
         border-radius: 8px;
         transition: all 0.2s ease;
     }

     .step-item:last-child {
         margin-bottom: 0;
     }

     .step-item:hover {
         transform: translateX(5px);
         box-shadow: 0 2px 8px rgba(233, 30, 99, 0.1);
     }

     .step-number {
         width: 28px;
         height: 28px;
         background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
         color: white;
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         font-weight: 700;
         font-size: 0.875rem;
         flex-shrink: 0;
     }

     .step-text {
         flex: 1;
         font-size: 0.9rem;
         color: var(--text-dark);
         padding-top: 0.25rem;
     }

     /* Button */
     .btn-add-category {
         background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
         color: white;
         border: none;
         padding: 0.875rem 2rem;
         font-size: 1rem;
         font-weight: 600;
         border-radius: 10px;
         box-shadow: 0 4px 16px rgba(233, 30, 99, 0.3);
         transition: all 0.3s ease;
         display: inline-flex;
         align-items: center;
         gap: 0.75rem;
     }

     .btn-add-category:hover {
         transform: translateY(-2px);
         box-shadow: 0 6px 20px rgba(233, 30, 99, 0.4);
     }

     .btn-add-category i {
         font-size: 1.25rem;
     }

     /* Help Text */
     .help-text {
         display: flex;
         align-items: center;
         gap: 0.5rem;
         padding: 1rem;
         background: #e3f2fd;
         border-radius: 8px;
         margin-top: 2rem;
         border-left: 4px solid #2196f3;
     }

     .help-text i {
         color: #2196f3;
         font-size: 1.25rem;
     }

     .help-text-content {
         flex: 1;
     }

     .help-text-title {
         font-weight: 600;
         color: #1976d2;
         font-size: 0.875rem;
         margin-bottom: 0.25rem;
     }

     .help-text-description {
         font-size: 0.85rem;
         color: #1565c0;
         margin: 0;
     }

     /* Responsive */
     @media (max-width: 768px) {
         .empty-state-card {
             padding: 2rem 1.5rem;
         }

         .empty-state-title {
             font-size: 1.5rem;
         }

         .empty-state-icon {
             width: 100px;
             height: 100px;
         }

         .empty-state-icon i {
             font-size: 3rem;
         }
     }
 </style>


 <div class="empty-state-container">
     <div class="empty-state-card">
         <!-- Icon -->
         <div class="empty-state-icon">
             <i class="bi bi-file-earmark-text"></i>
         </div>

         <!-- Title -->
         <h2 class="empty-state-title">No Letterhead Categories Found</h2>

         <!-- Description -->
         <p class="empty-state-description">
             It looks like you haven't created any letterhead categories yet. Create your first category to start
             organizing your letterhead templates.
         </p>

         <!-- Steps Guide -->
         <div class="steps-list">
             <div class="steps-title">Quick Start Guide</div>
             <div class="step-item">
                 <div class="step-number">1</div>
                 <div class="step-text">Click the "Add Category" button below</div>
             </div>
             <div class="step-item">
                 <div class="step-number">2</div>
                 <div class="step-text">Enter a name for your category</div>
             </div>
             <div class="step-item">
                 <div class="step-number">3</div>
                 <div class="step-text">Start adding letterhead templates</div>
             </div>
         </div>

         <!-- Action Button -->
         <button type="button" class="btn-add-category" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
             <i class="bi bi-plus-circle"></i>
             Add Letterhead Category
         </button>

         <!-- Help Text -->
         <div class="help-text">
             <i class="bi bi-info-circle"></i>
             <div class="help-text-content">
                 <div class="help-text-title">Need Help?</div>
                 <p class="help-text-description">Categories help you organize different types of letterheads like
                     prescriptions, reports, certificates, etc.</p>
             </div>
         </div>
     </div>
 </div>


 @include('components.modals.letterhead-category-modal')

 {{-- <script>
     document.addEventListener('DOMContentLoaded', function() {
         const form = document.getElementById('addCategoryForm');

         // Form submission handler
         form.addEventListener('submit', function(e) {
             e.preventDefault();

             // Get form data
             const formData = new FormData(form);
             const data = Object.fromEntries(formData.entries());

             console.log('Category data:', data);

             // Here you would typically send the data to your server
             /*
             fetch('/api/letterhead-categories', {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                 },
                 body: JSON.stringify(data)
             })
             .then(response => response.json())
             .then(result => {
                 console.log('Success:', result);
                 // Close modal and refresh page or update UI
                 const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
                 modal.hide();
                 // Optionally reload or update the page
                 location.reload();
             })
             .catch(error => {
                 console.error('Error:', error);
                 alert('An error occurred while saving the category.');
             });
             */

             // For demo purposes
             alert('Category would be saved here!\n\nName: ' + data.category_name);

             // Close modal
             const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
             modal.hide();

             // Reset form
             form.reset();
         });
     });
 </script> --}}
