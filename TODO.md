# TODO: Auto-fill Issuing Form on Plate/Chassis Input

## Steps to Complete

1. **Add searchVehicle method to IssuingController**
   - Create a new method in app/Http/Controllers/Company/IssuingController.php
   - Method should accept plate_number or chassis_number as query params
   - Query the issuing table for the latest record matching the plate or chassis
   - Return JSON response with the issuing data if found

2. **Add route for searchVehicle**
   - Add a new route in routes/web.php under company/issuing/search-vehicle
   - Route should point to IssuingController@searchVehicle
   - Ensure it's protected by auth:companys middleware

3. **Add JavaScript to the view**
   - Modify resources/views/comapny/Issuing/index.blade.php
   - Add event listeners on #plate_number and #chassis_number for blur/change
   - Make AJAX GET request to the search route with the entered value
   - If data is returned, auto-fill the form fields with the previous issuing data

4. **Test the functionality**
   - Enter a plate_number or chassis_number that has a previous issuing
   - Verify that the form auto-fills with the previous data
   - Ensure it only fills if a match is found, and handles no match gracefully

5. **Handle edge cases**
   - If multiple issuings exist for the same vehicle, use the latest one
   - Ensure the fill only happens for the same vehicle (same plate or chassis)
   - Add loading indicator or feedback to user during AJAX call

## Completed Steps

- [x] Add searchVehicle method to IssuingController
- [x] Add route for searchVehicle
- [x] Add JavaScript to the view
- [ ] Test the functionality
- [ ] Handle edge cases
