<?php

namespace App\Livewire;
use App\Models\Todo;
use Exception;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
class TodoList extends Component
{
    use WithFileUploads;
    use WithPagination;

    #[Rule('required|min:2|max:20')]
    public $name;
    public $search;
    public $editTodoId;
    #[Rule('required|min:2|max:20')]
    public $editTodoName;
    #[Rule('nullable|sometimes|image|max:1024')]
    public $image;
    public function create(){
        $validated = $this->validateOnly('name');
        if($this->image){
            $validated['image'] = $this->image->store('uploads','public');
        }
        Todo::create($validated);
        $this->reset('name','image');
        request()->session()->flash('success','Todo Created Successfully');
        $this->resetPage();
    }
    public function toggle($id){
        $todo = Todo::find($id);
        $todo->complete = !$todo->complete;
        $todo->save();
    }
    public function edit($id){
        $this->editTodoId = $id;
        $this->editTodoName = Todo::find($id)->name;

    }
    public function cancelEdit(){
        $this->reset('editTodoId','editTodoName');
    }
    public function update(){
        $this->validateOnly('editTodoName');
        Todo::find($this->editTodoId)->update(
            [
                'name'=> $this->editTodoName
            ]
        );
        $this->cancelEdit();
    }
    public function delete($id){
        try{
            Todo::findOrFail($id)->delete();
        }catch(Exception $e){
            session()->flash('error','failed to delete todo');
            return;
        }
    }
    public function render()
    {
        $todos = Todo::latest()->where('name','like',"%{$this->search}%")->paginate(3);
        return view('livewire.todo-list',[
            'todos' =>$todos
        ]);
    }
}
