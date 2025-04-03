
<script>
        async function showSuggestions() {
            const input = document.getElementById('address').value;
            if (input.length < 3) {
                document.getElementById('suggestions').innerHTML = '';
                return;
            }

            const response = await fetch(`https://api.geoapify.com/v1/geocode/autocomplete?text=${encodeURIComponent(input)}&apiKey=21576e6c288144af90743449e8420ec7`);
            const data = await response.json();
            const suggestions = data.features.map(feature => `
            <div class="autocomplete-suggestion" 
                 data-city="${feature.properties.city}" 
                 data-address_line1="${feature.properties.address_line1}" 
                 data-address_line2="${feature.properties.address_line2}" 
                 data-postcode="${feature.properties.postcode}" 
                 data-state="${feature.properties.state}" 
                 data-country="${feature.properties.country}">
                ${feature.properties.formatted}
            </div>
            `);



            document.getElementById('suggestions').innerHTML = suggestions.join('');

            document.querySelectorAll('.autocomplete-suggestion').forEach(item => {
            item.addEventListener('click', event => {
                document.getElementById('address').value = event.target.innerText;
                document.getElementById('city').value = event.target.getAttribute('data-city');
                document.getElementById('state').value = event.target.getAttribute('data-state');
                document.getElementById('address_line1').value = event.target.getAttribute('data-address_line1');
                document.getElementById('address_line2').value = event.target.getAttribute('data-address_line2');
                document.getElementById('postcode').value = event.target.getAttribute('data-postcode');
                document.getElementById('country').value = event.target.getAttribute('data-country');
                document.getElementById('suggestions').innerHTML = ''; // Clear suggestions after selection
            });

            });
        }
</script>
