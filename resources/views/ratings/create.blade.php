<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Rate Your Ride') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Ride Summary -->
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Ride Summary</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ $ride->pickup_address }} → {{ $ride->destination_address }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $ride->created_at->format('M j, Y g:i A') }} • ₱{{ $ride->fare }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('ratings.store', $ride) }}">
                        @csrf
                        
                        <!-- Who to rate -->
                        <input type="hidden" name="type" value="{{ Auth::id() === $ride->passenger_id ? 'driver' : 'passenger' }}">

                        <!-- Star Rating -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                How was your 
                                @if(Auth::id() === $ride->passenger_id)
                                    driver {{ $ride->driver->user->name }}?
                                @else
                                    passenger {{ $ride->passenger->name }}?
                                @endif
                            </label>
                            
                            <div class="flex justify-center space-x-2 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" 
                                            class="rating-star text-4xl focus:outline-none"
                                            data-rating="{{ $i }}">
                                        <span class="star-empty">☆</span>
                                        <span class="star-filled hidden">⭐</span>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="ratingValue" required>
                            <div id="ratingText" class="text-center text-lg font-semibold text-gray-600 dark:text-gray-400">
                                Tap stars to rate
                            </div>
                        </div>

                        <!-- Comment -->
                        <div class="mb-6">
                            <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Optional Comment
                            </label>
                            <textarea id="comment" 
                                      name="comment" 
                                      rows="4"
                                      placeholder="Share your experience (optional)..."
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" 
                                    id="submitButton"
                                    disabled
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                                Submit Rating
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedRating = 0;
        const ratingTexts = {
            1: 'Poor 😞',
            2: 'Fair 😐', 
            3: 'Good 🙂',
            4: 'Very Good 😊',
            5: 'Excellent 🤩'
        };

        document.querySelectorAll('.rating-star').forEach(star => {
            star.addEventListener('click', function() {
                selectedRating = parseInt(this.dataset.rating);
                
                // Update all stars
                document.querySelectorAll('.rating-star').forEach((s, index) => {
                    const starIndex = parseInt(s.dataset.rating);
                    const emptyStar = s.querySelector('.star-empty');
                    const filledStar = s.querySelector('.star-filled');
                    
                    if (starIndex <= selectedRating) {
                        emptyStar.classList.add('hidden');
                        filledStar.classList.remove('hidden');
                    } else {
                        emptyStar.classList.remove('hidden');
                        filledStar.classList.add('hidden');
                    }
                });
                
                // Update hidden input and text
                document.getElementById('ratingValue').value = selectedRating;
                document.getElementById('ratingText').textContent = ratingTexts[selectedRating];
                document.getElementById('ratingText').className = 'text-center text-lg font-semibold ' + 
                    (selectedRating >= 4 ? 'text-green-600' : 
                     selectedRating >= 3 ? 'text-yellow-600' : 'text-red-600');
                
                // Enable submit button
                document.getElementById('submitButton').disabled = false;
            });
        });
    </script>
</x-app-layout>