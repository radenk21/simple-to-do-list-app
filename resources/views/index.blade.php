<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To Do List App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-[#94a3f8]">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <div class="container mx-auto my-10">
        <h1 class="text-center text-4xl font-bold text-gray-900 mb-8">To Do List</h1>
    
        <div class="w-full md:w-2/3 lg:w-1/2 mx-auto">
            <div class="bg-white shadow-lg rounded-2xl p-8">
                <form method="POST" action="{{ route('tasks.store') }}" id="shop-form" class="mb-6">
                    @csrf
                    @method('post')
                    <div class="flex items-center">
                        <input 
                            type="text" 
                            class="flex-grow px-4 py-2 rounded-l-lg border border-gray-300 focus:outline-none focus:border-indigo-500" 
                            id="list" 
                            name="list" 
                            placeholder="Add new task" 
                            value="{{ old('list') }}" 
                            required>
                        <button 
                            class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-6 rounded-r-lg transition-colors duration-200 flex items-center" 
                            type="submit">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add
                        </button>
                    </div>
                </form>
    
                <div class="relative overflow-x-auto mt-6">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="text-xs uppercase text-gray-600 bg-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center">To Do</th>
                                <th scope="col" class="px-6 py-3 text-center">Status</th>
                                <th scope="col" class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tasks as $task)
                                <tr class="bg-white border-b last:border-none hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 font-medium text-gray-900 text-center">
                                        {{ $task->list }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($task->status == 'Done')
                                            <span id="display_status-{{ $task->id }}" class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">Done</span>
                                        @elseif($task->status == 'Doing')
                                            <span id="display_status-{{ $task->id }}" class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">Doing</span>
                                        @else
                                            <span id="display_status-{{ $task->id }}" class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">Soon</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-4">
                                            <select id="status-{{ $task->id }}" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option disabled>Choose status</option>
                                                @foreach (['Done', 'Doing', 'Soon'] as $status)
                                                    <option value="{{ $status }}" {{ $status == $task->status ? 'selected' : '' }}>{{ $status }}</option>                                        
                                                @endforeach
                                            </select>
                                            <button 
                                                data-modal-target="crud-modal-{{ $task->id }}" 
                                                data-modal-toggle="crud-modal-{{ $task->id }}" 
                                                class="text-indigo-500 hover:text-indigo-700 transition-colors duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m4 8H9m2-16H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2h-4m-4 0V4m0 4v4m0 0H9m6 0h-2m-2-4V4m0 4v4"></path></svg>
                                            </button>
                                            <form action="{{ route('tasks.destroy', $task->id) }}" method="post" class="flex justify-center align-items-center">
                                                @csrf
                                                @method('delete')
                                                <button class="text-red-500 hover:text-red-700 transition-colors duration-200" type="submit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">No tasks found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    


    @forelse ($tasks as $task)
        <!-- Main modal -->
        <div id="crud-modal-{{ $task->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden shadow-md fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Edit To Do
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal-{{ $task->id }}">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="p-4 md:p-5">
                        @csrf
                        @method('put')
                        <div class="grid gap-4 mb-4 grid-cols-2">
                            <div class="col-span-2">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">What to Do?</label>
                                <input type="text" name="list" id="list" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="{{ $task->list }}" value="{{ $task->list }}">
                            </div>
                        </div>
                        <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Edit Item
                        </button>
                    </form>
                </div>
            </div>
        </div> 
    @empty
        
    @endforelse


    @if (session('success-store'))
        <script>
            toastr.success("{{ session('success-store') }}")
        </script>
    @endif
    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}")
        </script>
    @endif
    
    <script>
        document.querySelectorAll('select[name="status"]').forEach(select => {
        select.addEventListener('change', async (e) => {
            const id = e.target.id.split('-')[1]; // Ekstrak ID dari elemen select
            const status = e.target.value;
            console.log(id + ' ' + status);
            let classList;
            if (status === 'Doing') {
                classList = 'bg-yellow-100 text-yellow-800';
            } else if (status === 'Soon') {
                classList = 'bg-red-100 text-red-800';
            } else {
                classList = 'bg-green-100 text-green-800';
            }
            console.log(id);

            // Temukan dan ubah kelas elemen <span> yang sesuai dengan ID
            const displayStatus = document.getElementById(`display_status-${id}`);
            // console.log(displayStatus);
            displayStatus.className = ` ${classList} text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:${classList.split(' ')[0]}-900 dark:${classList.split(' ')[1]}-300`;
            displayStatus.innerText = status;
            try {
                const response = await fetch(`/tasks/status/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status })
                });
                if (response.ok) {
                    console.log(id);
                    const displayStatus = document.getElementById(`display_status-${id}`);
                    
                    toastr.success('Berhasil merubah status!!!');
                } else {
                    console.log("Failed to update status");
                    toastr.error('Gagal merubah status!!!');
                }
            } catch (error) {
                console.log(error);
            }
            });
        });

    </script>
</body>
</html>