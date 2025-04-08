document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('select-all-items');
    const itemCheckboxes = document.querySelectorAll('.cart-item-checkbox');
    const removeSelectedBtn = document.getElementById('remove-selected-btn');

    function toggleRemoveButton() {
        // Ensure removeSelectedBtn exists before trying to set disabled property
        if (!removeSelectedBtn) return;
        const anyChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
        removeSelectedBtn.disabled = !anyChecked;
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            toggleRemoveButton();
        });
    }

    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            if (selectAllCheckbox) { // Check if selectAllCheckbox exists
                if (!checkbox.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    // Check if all items are checked
                    const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                    if (allChecked) {
                        selectAllCheckbox.checked = true;
                    }
                }
            }
            toggleRemoveButton();
        });
    });

    // Initial state check
    toggleRemoveButton();

    // Bulk remove form and modal logic
    const removeMultipleForm = document.getElementById('remove-multiple-form');
    const confirmBulkRemoveButton = document.getElementById('confirm-bulk-remove-button');
    const bulkRemoveMessageSpan = document.getElementById('bulk-remove-confirmation-message');

    // Ensure all elements exist before adding listeners (using nested ifs)
    if (removeMultipleForm) {
        if (confirmBulkRemoveButton) {
            if (bulkRemoveMessageSpan) {
                removeMultipleForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Prevent default form submission
            const selectedCount = document.querySelectorAll('.cart-item-checkbox:checked').length;
            if (selectedCount === 0) {
                alert('Please select items to remove.');
                return false;
            }

            // Update modal message
            bulkRemoveMessageSpan.textContent = `Are you sure you want to remove the selected ${selectedCount} ${selectedCount === 1 ? 'item' : 'items'}?`;

            // Dispatch an event to open the Alpine modal
                    window.dispatchEvent(new CustomEvent('open-bulk-remove-modal'));
                });

                confirmBulkRemoveButton.addEventListener('click', function() {
                    // Submit the form when confirmation button is clicked
                    removeMultipleForm.submit();
                });
            }
        }
    }

     // --- Keep the existing single item remove confirmation ---
     // (No changes needed here, it uses a separate form and onclick confirm)

});
