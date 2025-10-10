# TODO: Fix Undefined Variable $faculties in InternjobController@index

## Approved Plan Steps
- [x] Define $faculties array in index() method (hardcoded from jobs() method)
- [x] Add $user definition with Auth guard and load relationships
- [x] Add $jobs query with search/category filters, order by created_at desc, limit 6
- [x] Ensure $category_counts foreach works (after $faculties defined)
- [ ] Test: Run server and access / to verify no errors, categories/jobs display correctly

# TODO: Update job card logos to use uploaded images from storage

## Approved Plan Steps
- [x] Edit resources/views/jobs.blade.php: change img src to use $job->logo with fallback
- [x] Edit resources/views/welcome.blade.php: change img src to use $job->logo with fallback
- [x] Run php artisan storage:link to ensure storage is linked
- [ ] Test: Upload logo via Filament and verify it displays in cards
