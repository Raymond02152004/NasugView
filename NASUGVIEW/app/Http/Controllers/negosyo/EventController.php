<?php

namespace App\Http\Controllers\negosyo;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Question;
use App\Models\Choice;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $forms = Form::orderBy('created_at', 'asc')->get();
        return view('negosyo.events', compact('forms'));
    }

    public function create()
    {
        return view('negosyo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array',
        ]);

        $form = Form::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        foreach ($request->questions as $index => $q) {
            $question = Question::create([
                'form_id' => $form->form_id,
                'question_text' => $q['text'],
                'question_type' => $q['type'],
                'position' => $index + 1,
            ]);

            if ($q['type'] === 'multiple_choice' && isset($q['choices'])) {
                foreach ($q['choices'] as $choiceIndex => $choiceText) {
                    Choice::create([
                        'question_id' => $question->question_id,
                        'choice_text' => $choiceText,
                        'position' => $choiceIndex + 1,
                    ]);
                }
            }
        }

        return redirect()->route('negosyo.events.index')->with('success', 'Event created successfully!');
    }

    public function show($id)
    {
        $form = Form::with('questions.choices')->findOrFail($id);
        return view('negosyo.show', compact('form'));
    }

    public function edit($id)
    {
        $form = Form::with('questions.choices')->findOrFail($id);
        return view('negosyo.edit', compact('form'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array',
        ]);

        $form = Form::findOrFail($id);
        $form->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Delete old questions and choices
        foreach ($form->questions as $question) {
            $question->choices()->delete();
        }
        $form->questions()->delete();

        // Add new questions and choices
        foreach ($request->questions as $index => $q) {
            $question = Question::create([
                'form_id' => $form->form_id,
                'question_text' => $q['text'],
                'question_type' => $q['type'],
                'position' => $index + 1,
            ]);

            if ($q['type'] === 'multiple_choice' && isset($q['choices'])) {
                foreach ($q['choices'] as $choiceIndex => $choiceText) {
                    Choice::create([
                        'question_id' => $question->question_id,
                        'choice_text' => $choiceText,
                        'position' => $choiceIndex + 1,
                    ]);
                }
            }
        }

        return redirect()->route('negosyo.events.index')->with('success', 'Event updated successfully!');
    }

    public function destroy($id)
    {
        $form = Form::findOrFail($id);

        foreach ($form->questions as $question) {
            $question->choices()->delete();
        }
        $form->questions()->delete();

        $form->delete();

        return redirect()->route('negosyo.events.index')->with('success', 'Event deleted successfully!');
    }
}
