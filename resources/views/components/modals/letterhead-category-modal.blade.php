 <style>
     /* Modal Styling */
     :root {
         --primary-color: #750096;
         --primary-dark: #CB6CE6;
         --secondary-color: #6c757d;
         --border-color: #dee2e6;
         --bg-light: #f8f9fa;
         --text-dark: #212529;
         --text-muted: #6c757d;
     }

     .modal-content {
         border: none;
         border-radius: 12px;
         box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
     }

     .modal-header {
         background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
         color: white;
         padding: 1.5rem;
         border: none;
         border-radius: 12px 12px 0 0;
     }

     .modal-title {
         font-weight: 600;
         font-size: 1.25rem;
         display: flex;
         align-items: center;
         gap: 0.75rem;
     }

     .btn-close {
         filter: brightness(0) invert(1);
         opacity: 0.9;
     }

     .btn-close:hover {
         opacity: 1;
     }

     .form-label {
         font-weight: 600;
         color: var(--text-dark);
         margin-bottom: 0.5rem;
         font-size: 0.9rem;
     }

     .form-label .required {
         color: var(--primary-color);
     }

     .form-control {
         border: 1px solid var(--border-color);
         border-radius: 8px;
         padding: 0.625rem 0.875rem;
         font-size: 0.9rem;
         transition: all 0.2s ease;
     }

     .form-control:focus {
         border-color: var(--primary-color);
         box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.15);
     }

     .modal-footer {
         border-top: 1px solid var(--border-color);
         /* padding: 1rem 1.5rem; */
     }

     .btn-cancel {
         background: white;
         color: var(--text-muted);
         border: 1px solid var(--border-color);
         padding: 0.625rem 1.5rem;
         border-radius: 8px;
         font-weight: 500;
     }

     .btn-cancel:hover {
         background: var(--bg-light);
         border-color: var(--secondary-color);
     }

     .btn-save {
         background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
         color: white;
         border: none;
         padding: 0.625rem 1.5rem;
         border-radius: 8px;
         font-weight: 600;
     }

     .btn-save:hover {
         /* transform: translateY(-1px); */
         box-shadow: 0 4px 12px rgba(233, 30, 99, 0.3);
     }
 </style>


 <!-- Add Category Modal -->
 <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="addCategoryModalLabel">
                     <i class="bi bi-folder-plus"></i>
                     Add Letterhead Category
                 </h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body px-3">
                 <form id="addCategoryForm" action="{{ route('letterheadCategory.store') }}" method="POST">
                     @csrf
                     <div class="mb-3">
                         <label for="category_name" class="form-label">
                             Category Name <span class="required">*</span>
                         </label>
                         <input type="text" class="form-control" id="category_name" name="category_name"
                             placeholder="e.g., Prescription, Medical Report, Certificate" required>
                     </div>
                     <div class="modal-footer px-0">
                         <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                             <i class="bi bi-x-circle me-1"></i>
                             Cancel
                         </button>
                         <button type="submit" form="addCategoryForm" class="btn btn-save">
                             <i class="bi bi-check-circle me-1"></i>
                             Save
                         </button>
                     </div>
                 </form>
             </div>

         </div>
     </div>
 </div>
