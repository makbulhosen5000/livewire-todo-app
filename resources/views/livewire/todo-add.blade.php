<div>
    @if (session('error'))
    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
        <div class="flex">
          <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
          <div>
            <p class="font-bold">Error</p>
            <p class="text-sm">{{ session('error') }}</p>
          </div>
        </div>
      </div>
    @endif
    @if (session('success'))
    <span class="bg-green-600 rounded">{{ session('success') }}</span>
    @endif
    <div class="flex mb-4">
        <input wire:model.live.debounce.500mx='search' type="text" placeholder="Search a task"
            class="flex-1 rounded-l border-t border-b border-l text-gray-700 py-2 px-3 focus:outline-none">
        <button type="submit"
            class="bg-blue-500 text-white rounded-r border border-blue-500 px-4 py-2 hover:bg-blue-600 focus:outline-none">
            Search Todo
        </button>
    </div>
    <div class="flex mb-4">
        <input wire:model='name' type="text" placeholder="Add Todo..."
            class="flex-1 rounded-l border-t border-b border-l text-gray-700 py-2 px-3 focus:outline-none">
        <input wire:model='image' type="file"  accept="image/png,image/jpeg/,image/jpg" id="image">
        @error('image')
        <span class="text-gray-700 mt-1">{{$message}}</span>
        @enderror
        <button wire:click.prevent='create'  type="submit"
            class="bg-blue-500 text-white rounded-r border border-blue-500 px-4 py-2 hover:bg-blue-600 focus:outline-none">
            Add
        </button>
        
    </div>
    <div class="flex">
        @if($image)
            <img class="w-20 h-20 block" src="{{ $image->temporaryUrl() }}" alt="">
        @endif
        <span wire:loading wire:target="image" class="text-green-600">Loading...</span>
    </div>

    <div>
        @error('name')
        <span class="text-red-600">{{ $message }}</span>
        @enderror
    </div>
    
    
    <ul>
        @forelse ($todos as $todo)
        <li wire:key="{{ $todo->id }}" class="flex items-center justify-between bg-gray-200 rounded-lg py-2 px-4 mb-2">
           <div class="flex">
            @if ($todo->complete)
            <input wire:click="toggle({{ $todo->id }})" type="checkbox" class="mr-2" checked>
            @else
            <input wire:click="toggle({{ $todo->id }})" type="checkbox" class="mr-2">
            @endif
            @if ($editTodoId === $todo->id)
            <div>
            <input wire:model="editTodoName"  type="text" placeholder="Add a task..."
            class="flex-1 rounded-l border-t border-b border-l text-gray-700 py-2 px-3 focus:outline-none">
            @error('editTodoName')
            <span class="text-gray-700 mt-1">{{$message}}</span>
            </div>
            @enderror
            @else
            <span class="text-gray-700">{{ $todo->name }} <br>{{ $todo->created_at }}</span> 
            @endif
           </div>
            <div class="flex">
            <button wire:click="edit({{ $todo->id }})"
                class="text-blue-500 hover:text-blue-700 mr-2 focus:outline-none">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M16.84 3.716a1 1 0 0 0-1.413 0l-2.828 2.829-9.9 9.899a1 1 0 0 0-.291.464l-1.105 4.424a1 1 0 0 0 1.257 1.256l4.423-1.105a1 1 0 0 0 .464-.29l9.9-9.9 2.828-2.829a1 1 0 0 0 0-1.413zM6.05 17.95l.708-2.832 1.418 1.417-2.832.715zm6.82-6.822l-1.417-1.417 6.36-6.359 1.417 1.417-6.36 6.359z" />
                </svg>
            </button>
            <button wire:click="delete({{ $todo->id }})" class="text-red-500 hover:text-red-700 focus:outline-none">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zm5 12.586L12.586 15 10 12.414 7.414 15 5 12.586 7.586 10 5 7.414 7.414 5 10 7.586 12.586 5 15 7.414 12.414 10 15 12.586z" />
                </svg>
            </button>
            </div>                                                      
        </li>
        <div class="flex mb-2">
            @if($editTodoId === $todo->id)
            <button wire:click="update" class="bg-green-600 rounded mr-2 p-2">Update</button>
            <button wire:click="cancelEdit" class="bg-blue-600 rounded p-2">Cancel</button>
            @endif
        </div>
        @empty
        <li class="flex items-center justify-between bg-gray-200 rounded-lg py-2 px-4 mb-2">
            <span class="text-gray-700">Todo Not Found</span>
        </li>

        @endforelse
        {{ $todos->links() }}

    </ul>
</div>

