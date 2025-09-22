# Remove Return Book Functionality from Student and Teacher Pages

## Task: Restrict book return functionality to librarians only

### Changes Made:
- [x] Remove return routes for guru and siswa in routes/web.php
- [x] Update borrowings index view to only show return button for librarians
- [x] Add authorization check in BorrowingController return method
- [ ] Test the changes with different user roles

### Files Modified:
- routes/web.php
- resources/views/borrowings/index.blade.php
- app/Http/Controllers/BorrowingController.php
