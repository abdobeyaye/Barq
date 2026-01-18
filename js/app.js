// Calculate delivery fee when districts are selected
function calculateDeliveryFee() {
    const pickupDistrict = document.getElementById('pickup_district');
    const deliveryDistrict = document.getElementById('delivery_district');
    const feeDisplay = document.getElementById('delivery_fee_display');
    
    if (pickupDistrict && deliveryDistrict && feeDisplay) {
        const from = pickupDistrict.value;
        const to = deliveryDistrict.value;
        
        if (from && to) {
            fetch(`api.php?action=calculate_fee&from=${from}&to=${to}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        feeDisplay.value = data.fee + ' MRU';
                    } else {
                        feeDisplay.value = 'Error';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    feeDisplay.value = 'Error';
                });
        } else {
            feeDisplay.value = '';
        }
    }
}

// Attach event listeners for district selection
document.addEventListener('DOMContentLoaded', function() {
    const pickupDistrict = document.getElementById('pickup_district');
    const deliveryDistrict = document.getElementById('delivery_district');
    
    if (pickupDistrict && deliveryDistrict) {
        pickupDistrict.addEventListener('change', calculateDeliveryFee);
        deliveryDistrict.addEventListener('change', calculateDeliveryFee);
    }
});

// Modal functions for admin
function showAddPointsModal(userId, userName) {
    document.getElementById('modal_user_id').value = userId;
    document.getElementById('modal_user_name').textContent = userName;
    const modal = new bootstrap.Modal(document.getElementById('addPointsModal'));
    modal.show();
}

// Confirm before form submission
function confirmAction(message) {
    return confirm(message);
}
