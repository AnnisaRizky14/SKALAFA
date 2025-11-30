# TODO: Add Sub-sections and Questions to Questionnaire Creation

## Current Status
- Questionnaire creation form exists but lacks fields for adding sub-sections and questions
- Controller handles basic questionnaire data but not questions

## Tasks
- [ ] Update create.blade.php to add dynamic fields for sub-sections and questions
- [ ] Update QuestionnaireController store method to handle questions data
- [ ] Add JavaScript for dynamic addition/removal of sub-sections and questions
- [ ] Test the functionality

## Details
- Sub-sections: Allow multiple sub-sections per questionnaire, each linked to existing sub_categories
- Questions: Allow multiple questions per sub-section, with fields for question_text, order, is_required
- Use dynamic form fields with JavaScript to add/remove sections and questions
- Update validation to handle array inputs for questions
