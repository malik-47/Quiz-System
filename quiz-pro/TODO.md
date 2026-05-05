# Quiz Pro - Admin Panel Implementation

## Steps:
1. [x] Add role support: Updated auth/login.php signup (default 'user'), added DB migration comment.
2. [x] Update user/dashboard.php: Role check, show Admin Panel link if admin.
3. [x] Create admin/index.php: Admin dashboard with links, role protection.
4. [x] Create admin/add_question.php: Form to add questions (validation, textarea, selector).
5. [x] Create admin/manage_questions.php: List questions with delete buttons.
6. [x] Create admin/delete_question.php: Secure API to delete.
7. [x] Add role checks/security to admin pages (done).
8. [x] Test full flow.
9. [x] [DONE]

Progress: Admin panel complete! 

**Setup:** Run DB migration SQL from login.php comment. Signup user/admin@example.com → set role='admin' via SQL → login → Admin Panel → Add/manage questions.

Full app: User quiz + admin CRUD questions. Secure, styled, responsive.
