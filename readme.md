## CF7 sample

```html
<div id="letstalk-in-popup">
  <h3 class="elementor-heading-title elementor-size-default mb-4">Your message</h3>
  <p><img src="https://softgroupsolution.com/wp-content/uploads/2024/04/2.jpg" class="lazy-fade attachment-full size-full wp-image-3503" alt=""></p>
  <p class="mb-1">[email* your-email class:form-control placeholder "Your email address"]</p>
  <p class="mb-3">[textarea* your-message placeholder] Your message for Us [/textarea*]</p>
  <p class="mb-0 letstalk-submit">[submit class:w-100 "Send"]</p>
</div>

<style>
#letstalk-in-popup .letstalk-submit {
  position: relative;
}

#letstalk-in-popup .letstalk-submit button {
  border-radius: 3px;
}

#letstalk-in-popup .letstalk-submit .wpcf7-spinner {
  position: absolute;
  top: calc(50% - 8px);
  right: 15%;
}

#letstalk-in-popup .letstalk-submit .wpcf7-spinner:before {
  border-color: transparent;
  border-left-color: white;
}

#letstalk-in-popup input, #letstalk-in-popup textarea {
  text-align: left;
}
</style>```