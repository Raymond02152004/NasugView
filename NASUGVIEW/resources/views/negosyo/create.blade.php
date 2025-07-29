@extends('negosyo.negosyolayout')

@section('content')
<style>
  body {
    background-color: #d0e7cc;
    overflow-x: hidden;
  }
  .card-custom {
    max-width: 100%;
    margin: 3rem auto;
    padding: 2rem;
    border-radius: 32px;
    background-color: #ffffff;
    border: 2px solid #14532d;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    position: relative;
  }
  .card-section {
    background-color: #fff;
    border: 2px solid #14532d;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    padding: 2rem;
    margin-bottom: 1.5rem;
  }
  .card-custom h2, .card-custom h1, .card-custom h5 {
    font-family: 'Georgia', serif;
    font-weight: 700;
    color: #204020;
  }
</style>

<div class="container-fluid px-3">
  <div class="card card-custom">

    <h2 class="mb-4">Create Event</h2>

    <form action="{{ route('negosyo.events.store') }}" method="POST">
        @csrf

        <!-- Card Section: Event Title & Description -->
        <div class="card-section">
            <div class="mb-3">
                <label class="form-label fw-semibold">Event Title</label>
                <input type="text" class="form-control" name="title" required placeholder="Enter event title">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Event Description</label>
                <textarea class="form-control" name="description" rows="3" placeholder="Describe your event"></textarea>
            </div>
        </div>

        <h5 class="fw-bold mt-4">Questions</h5>
        <div id="questionsContainer"></div>

        <button type="button" class="btn btn-outline-success mt-3" style="border-color: #14532d; color: #14532d;" onclick="addQuestion()">Add Question</button>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-success">Create Event</button>
        </div>
    </form>

  </div>
</div>

<script>
    let questionCount = 0;

    function addQuestion() {
        questionCount++;
        const container = document.getElementById('questionsContainer');
        const questionDiv = document.createElement('div');
        questionDiv.classList.add('card-section');
        questionDiv.innerHTML = `
            <div class="mb-2">
                <label class="form-label fw-semibold">Question #${questionCount}</label>
                <input type="text" class="form-control" name="questions[${questionCount}][text]" placeholder="Enter your question" required>
            </div>
            <div class="mb-2">
                <label class="form-label fw-semibold">Question Type</label>
                <select class="form-select" name="questions[${questionCount}][type]" onchange="toggleChoices(this, ${questionCount})" required>
                    <option value="text">Text Answer</option>
                    <option value="multiple_choice">Multiple Choice</option>
                </select>
            </div>
            <div class="choices-container d-none" id="choices-${questionCount}">
                <label class="form-label fw-semibold">Choices</label>
                <div class="choices-list"></div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addChoice(${questionCount})">Add Choice</button>
            </div>
        `;
        container.appendChild(questionDiv);
    }

    function toggleChoices(select, questionId) {
        const choicesContainer = document.getElementById(`choices-${questionId}`);
        choicesContainer.classList.toggle('d-none', select.value !== 'multiple_choice');
    }

    function addChoice(questionId) {
        const choicesList = document.querySelector(`#choices-${questionId} .choices-list`);
        const choiceCount = choicesList.children.length + 1;
        const choiceDiv = document.createElement('div');
        choiceDiv.classList.add('input-group', 'mb-2');
        choiceDiv.innerHTML = `
            <span class="input-group-text">Choice #${choiceCount}</span>
            <input type="text" class="form-control" name="questions[${questionId}][choices][]" placeholder="Enter choice" required>
        `;
        choicesList.appendChild(choiceDiv);
    }
</script>
@endsection
