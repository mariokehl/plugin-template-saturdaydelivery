/**
 * Main function to handle saturday delivery
 */
 function SaturdayDelivery() {
  this.getConfig = function () {
    const json = JSON.parse(document.getElementById('saturday-delivery--config').textContent)
    return json
  },
  this.selectSaturdayDelivery = function() {
    const cb = document.getElementById('saturdayDeliveryCheckboxId')
    if (cb.checked === false) {
      cb.checked = true
    }
    this.onClickSaturdayDeliveryCheckbox()
  },
  this.deselectSaturdayDelivery = function() {
    const cb = document.getElementById('saturdayDeliveryCheckboxId')
    if (cb.checked === true) {
      cb.checked = false
    }
    this.onClickSaturdayDeliveryCheckbox()
  },
  this.onClickSaturdayDeliveryCheckbox = function() {
    let additionalFee = false
    if (document.getElementById('saturdayDeliveryCheckboxId').checked === true) {
      additionalFee = true
    }
    let selection = (document.querySelector('input[name="selectedSaturday"]:checked') !== null)
    $.ajax({
      url: '/plugin/saturday-delivery/select/',
      method: 'POST',
      data: {
        selectedSaturday: selection ? document.querySelector('input[name="selectedSaturday"]:checked').value : null,
        active: additionalFee
      },
      success: function () {
        document.dispatchEvent(new CustomEvent('afterPaymentMethodChanged'))
        document.dispatchEvent(new CustomEvent('afterSaturdayDeliverySelected'))
      }
    })
  },
  this.handleShippingProfileChange = function(shippingProfileId) {
    const config = this.getConfig()
    if (config.shippingProfiles.length === 0) {
      return
    }
    if (config.shippingProfiles.includes(shippingProfileId)) {
      document.getElementById('saturday-delivery--wrap').style.display = 'block'
    } else {
      document.getElementById('saturday-delivery--wrap').style.display = 'none'
      this.deselectSaturdayDelivery()
    }
  }
}

// Initialize
var saturdayDelivery = new SaturdayDelivery();

// User changes the shipping profile
document.addEventListener('afterShippingProfileChanged', (e) => {
  const shippingProfileId = e.detail;
  saturdayDelivery.handleShippingProfileChange(shippingProfileId);
}, false);