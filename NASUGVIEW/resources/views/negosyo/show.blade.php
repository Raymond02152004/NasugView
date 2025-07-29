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
  .btn-custom-green {
    background-color: #14532d;
    color: #ffffff;
  }
  .btn-custom-green:hover {
    background-color: #0f3f1f;
    color: #ffffff;
  }
</style>


<div class="container-fluid px-3">
  <div class="card card-custom">

    <div class="card-section">
      <h1 class="fw-bold">{{ $form->title }}</h1>
      <p class="text-muted">{{ $form->description }}</p>
    </div>

    <h5 class="fw-bold mt-4">Questions</h5>

    <form>
      @forelse ($form->questions as $question)
        <div class="card-section">
          <p class="fw-semibold mb-3">
            {{ $question->position }}. {{ $question->question_text }}
          </p>

          @if ($question->question_type === 'text')
            <textarea class="form-control" name="answers[{{ $question->question_id }}]" rows="2" placeholder="Enter your answer..."></textarea>
          @elseif ($question->question_type === 'multiple_choice')
            @foreach ($question->choices as $choice)
              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="answers[{{ $question->question_id }}]" id="choice-{{ $choice->choice_id }}" value="{{ $choice->choice_id }}">
                <label class="form-check-label" for="choice-{{ $choice->choice_id }}">
                  {{ $choice->choice_text }}
                </label>
              </div>
            @endforeach
          @endif
        </div>
      @empty
        <p class="text-muted">No questions added for this event.</p>
      @endforelse

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('negosyo.events.index') }}" class="btn btn-custom-green">
                <i class="bi bi-arrow-left-circle me-1"></i> Back to Events
            </a>
        </div>
    </form>

  </div>
</div>
@endsection
