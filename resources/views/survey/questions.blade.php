@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Navigation Header -->
    <header class="bg-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between">
                <div class="flex items-center space-x-2 sm:space-x-4 flex-1 min-w-0 w-full sm:w-auto mb-2 sm:mb-0">
                    <a href="{{ route('survey.questionnaires', $questionnaire->faculty) }}" class="text-gray-600 hover:text-primary flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <img src="{{ asset('storage/unib-logo.png') }}" alt="Logo UNIB" class="h-8 w-8 sm:h-10 sm:w-10 flex-shrink-0">
                    <div class="min-w-0 flex-1">
                        <h1 class="text-lg sm:text-xl font-bold text-primary truncate">{{ $questionnaire->title }}</h1>
                        <p class="text-xs sm:text-sm text-gray-600 truncate">{{ $questionnaire->faculty->name }}</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-0 sm:ml-2 w-full sm:w-auto">
                    <p class="text-xs sm:text-sm text-gray-600">Langkah 3 dari 3</p>
                    <div class="w-full sm:w-32 progress-bar mt-1 sm:mt-2">
                        <div class="progress-fill" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Progress Section -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-2 sm:space-y-0">
                <div class="flex items-center space-x-3 sm:space-x-4 flex-1 min-w-0">
                    <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                        <span id="current-question">0</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-800 truncate">
                            Pertanyaan <span id="question-counter">0</span> dari {{ $questionnaire->getTotalQuestions() }}
                        </p>
                        <div class="w-full sm:w-64 progress-bar mt-1">
                            <div id="question-progress" class="progress-fill" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
                <div class="text-left sm:text-right flex-shrink-0">
                    <p class="text-xs text-gray-500">Estimasi: {{ $questionnaire->estimated_duration }} menit</p>
                    <p class="text-xs text-gray-500">Token: {{ substr($response->response_token, 0, 8) }}...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <form id="survey-form" action="{{ route('survey.submit') }}" method="POST">
            @csrf
            <input type="hidden" name="response_token" value="{{ $response->response_token }}">
            
            <!-- Instructions -->
            @if($questionnaire->instructions)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-1 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-800 mb-2">Petunjuk Pengisian</h3>
                        <div class="text-blue-700 text-sm leading-relaxed">
                            {!! nl2br(e($questionnaire->instructions)) !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Questions by Subcategory -->
            @foreach($subcategories as $subcategory)
                @php $questions = $questionsGrouped->get($subcategory->id, collect()) @endphp
                @if($questions->isNotEmpty())
                <div class="subcategory-section mb-12" data-subcategory="{{ $subcategory->id }}">
                    <!-- Subcategory Header -->
                    <div class="bg-white rounded-t-xl border-l-4 border-primary p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-primary mb-2">
                                    {{ $subcategory->name }}
                                </h2>
                                @if($subcategory->description)
                                <p class="text-gray-600">{{ $subcategory->description }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold">
                                    {{ $loop->iteration }}
                                </div>
                                <p class="text-xs text-gray-500 mt-2">{{ $questions->count() }} pertanyaan</p>
                            </div>
                        </div>
                    </div>

                    <!-- Questions -->
                    <div class="bg-white rounded-b-xl shadow-lg border-t border-gray-100">
                        @foreach($questions as $question)
                        <div class="question-item p-6 border-b border-gray-100 last:border-b-0" data-question="{{ $question->id }}">
                            <div class="flex items-start space-x-4">
                                <!-- Question Number -->
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-sm font-semibold text-gray-600 flex-shrink-0 mt-2">
                                    {{ $question->order }}
                                </div>

                                <div class="flex-1">
                                    <!-- Question Text -->
                                    <h3 class="text-lg font-medium text-gray-800 mb-4 leading-relaxed">
                                        {{ $question->question_text }}
                                        @if($question->is_required)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </h3>

                                    <!-- Rating Scale -->
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-600 mb-3">Berikan penilaian Anda (1 = Sangat Tidak Puas, 5 = Sangat Puas):</p>
<div class="grid grid-cols-1 gap-2 sm:grid-cols-5 sm:gap-2">
                                            @for($rating = 1; $rating <= 5; $rating++)
<label class="rating-option cursor-pointer snap-center px-2 flex-shrink-0" style="min-width: unset;">
    <input type="radio"
           name="answers[{{ $question->id }}][rating]"
           value="{{ $rating }}"
           class="sr-only"
           {{ $question->is_required ? 'required' : '' }}>
    <div class="rating-card bg-gray-50 border-2 border-gray-200 rounded-lg p-3 text-center transition-all duration-200 hover:border-primary hover:bg-blue-50 min-h-[80px] flex flex-col justify-center">
                                                    <!-- Star Rating Visual -->
                                                    <div class="flex justify-center mb-1 sm:mb-2">
                                                        @for($star = 1; $star <= 5; $star++)
                                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $star <= $rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                             fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                        @endfor
                                                    </div>

                                                    <div class="text-lg sm:text-2xl font-bold text-gray-700 mb-1">{{ $rating }}</div>
                                                    <div class="text-xs text-gray-600 leading-tight">
                                                        @switch($rating)
                                                            @case(1) Sangat Tidak Puas @break
                                                            @case(2) Tidak Puas @break
                                                            @case(3) Cukup Puas @break
                                                            @case(4) Puas @break
                                                            @case(5) Sangat Puas @break
                                                        @endswitch
                                                    </div>
                                                </div>
                                            </label>
                                            @endfor
                                        </div>
                                    </div>

                                    <!-- Hidden Question ID -->
                                    <input type="hidden" name="answers[{{ $question->id }}][question_id]" value="{{ $question->id }}">

                                    <!-- Optional Comment -->
<div class="mt-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Komentar atau Saran (Opsional):
    </label>
    <textarea name="answers[{{ $question->id }}][comment]"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent resize-none text-sm sm:text-base"
              placeholder="Berikan komentar atau saran untuk perbaikan..."></textarea>
</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach

            <!-- Submit Section -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 text-center">
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">Selesaikan Survei</h3>
                <p class="text-gray-600 mb-6 text-sm sm:text-base">
                    Pastikan semua pertanyaan wajib telah dijawab sebelum mengirim survei.
                </p>

<div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
    <button type="button"
            onclick="reviewAnswers()"
            class="w-full sm:w-auto px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200 text-sm sm:text-base">
        Tinjau Jawaban
    </button>

    <button type="submit"
            id="submit-btn"
            class="w-full sm:w-auto px-8 py-3 btn-primary rounded-lg font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base">
        <span class="flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
            Kirim Survei
        </span>
    </button>
</div>

                <!-- Progress Summary -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Progress Pengisian:</span>
                        <span id="progress-text" class="font-semibold text-primary">0%</span>
                    </div>
                    <div class="w-full progress-bar mt-2">
                        <div id="overall-progress" class="progress-fill" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </form>
    </main>
</div>

<!-- Review Modal -->
<div id="review-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] sm:max-h-[80vh] overflow-hidden">
        <div class="p-4 sm:p-6 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-lg sm:text-2xl font-bold text-gray-800">Tinjau Jawaban Anda</h3>
                <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="p-4 sm:p-6 overflow-y-auto max-h-[60vh] sm:max-h-[60vh]">
            <div id="review-content">
                <!-- Review content will be populated by JavaScript -->
            </div>
        </div>
        <div class="p-4 sm:p-6 border-t bg-gray-50 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4">
            <button onclick="closeReviewModal()"
                    class="w-full sm:w-auto px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors text-sm sm:text-base">
                Kembali Edit
            </button>
            <button onclick="submitSurvey()"
                    class="w-full sm:w-auto px-8 py-2 btn-primary rounded-lg font-semibold text-sm sm:text-base">
                Kirim Survei
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let totalQuestions = {{ $questionnaire->getTotalQuestions() }};
let answeredQuestions = 0;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize progress tracking
    updateProgress();
    
    // Add event listeners to all rating inputs
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Update visual feedback
            updateRatingVisual(this);
            
            // Save progress locally
            saveProgress();
            
            // Update progress counters
            updateProgress();
            
            // Auto-save answer
            autoSaveAnswer(this);
        });
    });
    
    // Add event listeners to comment textareas
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.addEventListener('blur', function() {
            saveProgress();
            autoSaveAnswer(this);
        });
    });
    
    // Load saved progress
    loadSavedProgress();
});

function updateRatingVisual(radioInput) {
    const ratingOption = radioInput.closest('.rating-option');
    const ratingCard = ratingOption.querySelector('.rating-card');
    const allCards = radioInput.closest('.flex').querySelectorAll('.rating-card');
    
    // Reset all cards in this question
    allCards.forEach(card => {
        card.classList.remove('border-primary', 'bg-blue-100', 'ring-2', 'ring-primary', 'ring-opacity-50');
        card.classList.add('border-gray-200', 'bg-gray-50');
    });
    
    // Highlight selected card
    ratingCard.classList.remove('border-gray-200', 'bg-gray-50');
    ratingCard.classList.add('border-primary', 'bg-blue-100', 'ring-2', 'ring-primary', 'ring-opacity-50');
}

function updateProgress() {
    const questions = document.querySelectorAll('.question-item');
    answeredQuestions = 0;
    
    questions.forEach(question => {
        const radioInputs = question.querySelectorAll('input[type="radio"]');
        const isAnswered = Array.from(radioInputs).some(input => input.checked);
        
        if (isAnswered) {
            answeredQuestions++;
        }
    });
    
    const progressPercentage = Math.round((answeredQuestions / totalQuestions) * 100);
    
    // Update progress elements
    document.getElementById('current-question').textContent = answeredQuestions;
    document.getElementById('question-counter').textContent = answeredQuestions;
    document.getElementById('question-progress').style.width = progressPercentage + '%';
    document.getElementById('overall-progress').style.width = progressPercentage + '%';
    document.getElementById('progress-text').textContent = progressPercentage + '%';
    
    // Enable/disable submit button
    const submitBtn = document.getElementById('submit-btn');
    const allQuestionItems = document.querySelectorAll('.question-item');
    const requiredQuestionItems = Array.from(allQuestionItems).filter(item => item.querySelector('input[required]'));
    let answeredRequired = 0;

    requiredQuestionItems.forEach(questionItem => {
        const hasAnswer = questionItem.querySelector('input[type="radio"]:checked') !== null;
        if (hasAnswer) {
            answeredRequired++;
        }
    });

    const totalRequiredQuestions = requiredQuestionItems.length;

    if (answeredRequired >= totalRequiredQuestions) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

function saveProgress() {
    const formData = new FormData(document.getElementById('survey-form'));
    const answers = {};
    
    for (let [key, value] of formData.entries()) {
        answers[key] = value;
    }
    
    localStorage.setItem(`survey_progress_${window.csrf_token}`, JSON.stringify(answers));
}

function loadSavedProgress() {
    const savedData = localStorage.getItem(`survey_progress_${window.csrf_token}`);
    if (!savedData) return;
    
    try {
        const answers = JSON.parse(savedData);
        
        Object.keys(answers).forEach(key => {
            const input = document.querySelector(`[name="${key}"]`);
            if (input) {
                if (input.type === 'radio') {
                    if (input.value === answers[key]) {
                        input.checked = true;
                        updateRatingVisual(input);
                    }
                } else if (input.tagName === 'TEXTAREA') {
                    input.value = answers[key];
                }
            }
        });
        
        updateProgress();
    } catch (e) {
        console.error('Failed to load saved progress:', e);
    }
}

function autoSaveAnswer(input) {
    // Implement AJAX auto-save if needed
    // For now, just save to localStorage
    saveProgress();
}

function reviewAnswers() {
    const form = document.getElementById('survey-form');
    const formData = new FormData(form);
    const reviewContent = document.getElementById('review-content');
    
    let reviewHTML = '';
    const subcategories = document.querySelectorAll('.subcategory-section');
    
    subcategories.forEach(subcategory => {
        const subcategoryName = subcategory.querySelector('h2').textContent;
        reviewHTML += `<div class="mb-6">
            <h4 class="text-lg font-semibold text-primary mb-4 border-b pb-2">${subcategoryName}</h4>`;
        
        const questions = subcategory.querySelectorAll('.question-item');
        questions.forEach(question => {
            const questionId = question.dataset.question;
            const questionText = question.querySelector('h3').textContent.replace('*', '');
            const ratingInput = question.querySelector('input[type="radio"]:checked');
            const commentInput = question.querySelector('textarea');
            
            reviewHTML += `<div class="mb-4 p-4 bg-gray-50 rounded-lg">
                <p class="font-medium text-gray-800 mb-2">${questionText}</p>`;
            
            if (ratingInput) {
                const rating = ratingInput.value;
                const ratingText = getRatingText(rating);
                const stars = generateStars(rating);
                
                reviewHTML += `<div class="flex items-center mb-2">
                    <span class="text-sm text-gray-600 mr-2">Penilaian:</span>
                    ${stars}
                    <span class="ml-2 text-sm font-medium text-gray-800">${rating}/5 - ${ratingText}</span>
                </div>`;
            } else {
                reviewHTML += `<p class="text-red-500 text-sm">Belum dijawab</p>`;
            }
            
            if (commentInput && commentInput.value.trim()) {
                reviewHTML += `<div class="mt-2">
                    <span class="text-sm text-gray-600">Komentar:</span>
                    <p class="text-sm text-gray-800 italic">"${commentInput.value}"</p>
                </div>`;
            }
            
            reviewHTML += '</div>';
        });
        
        reviewHTML += '</div>';
    });
    
    reviewContent.innerHTML = reviewHTML;
    document.getElementById('review-modal').classList.remove('hidden');
    document.getElementById('review-modal').classList.add('flex');
}

function closeReviewModal() {
    document.getElementById('review-modal').classList.add('hidden');
    document.getElementById('review-modal').classList.remove('flex');
}

function getRatingText(rating) {
    const ratings = {
        '1': 'Sangat Tidak Puas',
        '2': 'Tidak Puas',
        '3': 'Cukup Puas',
        '4': 'Puas',
        '5': 'Sangat Puas'
    };
    return ratings[rating] || '';
}

function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        const filled = i <= rating;
        stars += `<svg class="w-4 h-4 ${filled ? 'text-yellow-400' : 'text-gray-300'}" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
        </svg>`;
    }
    return stars;
}

function submitSurvey() {
    closeReviewModal();
    showLoading();
    
    // Clear saved progress
    localStorage.removeItem(`survey_progress_${window.csrf_token}`);
    
    // Submit the form
    document.getElementById('survey-form').submit();
}

// Handle form submission
document.getElementById('survey-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    showLoading();
    
    // Validate required fields
    const requiredInputs = document.querySelectorAll('input[required]');
    let allValid = true;
    
    requiredInputs.forEach(input => {
        const questionItem = input.closest('.question-item');
        const isAnswered = questionItem.querySelectorAll('input[type="radio"]:checked').length > 0;
        
        if (!isAnswered) {
            allValid = false;
            questionItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
            questionItem.classList.add('border-2', 'border-red-300', 'bg-red-50');
            
            setTimeout(() => {
                questionItem.classList.remove('border-2', 'border-red-300', 'bg-red-50');
            }, 3000);
        }
    });
    
    if (!allValid) {
        hideLoading();
        showToast('Mohon lengkapi semua pertanyaan yang wajib diisi', 'error');
        return;
    }
    
    // Submit form
    this.submit();
});

// Auto-scroll to next question after answering
document.querySelectorAll('input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        setTimeout(() => {
            const currentQuestion = this.closest('.question-item');
            const nextQuestion = currentQuestion.nextElementSibling;
            
            if (nextQuestion && nextQuestion.classList.contains('question-item')) {
                const nextQuestionTop = nextQuestion.offsetTop - 100;
                window.scrollTo({
                    top: nextQuestionTop,
                    behavior: 'smooth'
                });
            }
        }, 500);
    });
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && e.ctrlKey) {
        // Ctrl+Enter to submit
        if (!document.getElementById('submit-btn').disabled) {
            document.getElementById('survey-form').dispatchEvent(new Event('submit'));
        }
    }
});

// Custom confirmation dialog on submit button click
document.getElementById('submit-btn').addEventListener('click', function(e) {
    e.preventDefault();

    // Create custom modal for confirmation
    const modalHtml = `
    <div id="confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6 text-center">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi</h3>
            <p class="mb-6">Anda yakin ingin mengirim hasil survei?</p>
            <div class="flex justify-center space-x-4">
                <button id="confirm-yes" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition">Ya</button>
                <button id="confirm-no" class="px-6 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">Batal</button>
            </div>
        </div>
    </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Add event listeners for modal buttons
    document.getElementById('confirm-yes').addEventListener('click', () => {
        // Remove modal
        document.getElementById('confirm-modal').remove();
        // Show loading overlay
        showLoading();
        // Submit the form
        document.getElementById('survey-form').submit();
    });

    document.getElementById('confirm-no').addEventListener('click', () => {
        // Remove modal
        document.getElementById('confirm-modal').remove();
    });
});

// Save progress periodically
setInterval(saveProgress, 30000); // Save every 30 seconds
</script>
@endpush

@endsection