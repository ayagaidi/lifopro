# TODO: Change Insurance Duration Calculation to Hours

## Overview
Modify the insurance duration calculation to use hours instead of days for full coverage. Keep form labels as "عدد الايام" (number of days) and input values in days, but calculate end date by adding equivalent hours (days * 24).

## Tasks
- [x] Update JavaScript in resources/views/dashbord/Issuing/index.blade.php to calculate end date by adding hours (days * 24) instead of days
- [x] Update JavaScript in resources/views/comapny/Issuing/index.blade.php to calculate end date by adding hours (days * 24) instead of days
- [x] Update JavaScript in resources/views/office/Issuing/index.blade.php to calculate end date by adding hours (days * 24) instead of days
- [x] Update app/Http/Controllers/Company/IssuingController.php to calculate end date by adding hours equivalent to days
- [x] Update app/Http/Controllers/Office/IssuingController.php to calculate end date by adding hours equivalent to days
- [x] Update app/Http/Controllers/Dashbord/IssuingController.php to calculate end date by adding hours equivalent to days (Not applicable - Dashbord doesn't have IssuingController)
- [ ] Test the changes to ensure end date is calculated correctly (e.g., 7 days = add 168 hours, end at same time as start)

## Notes
- Labels remain as "عدد الايام"
- Input values are in days
- Min/Max values remain in days (TUN: 7, EGY: 15, Max: 90)
- Database field insurance_days_number remains as is, storing days
- End date calculation: start_date + (days * 24 hours)
