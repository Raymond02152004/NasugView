@if($posts->count())  
  <div class="d-flex justify-content-center mt-4">
    <div style="width: 100%; max-width: 1500px;"> {{-- Facebook-like width --}}

      @foreach($posts as $post)
      @php
        $mediaList = json_decode(str_replace('\\', '/', $post->media_paths ?? '[]'));
        $mediaUrls = array_map(fn($m) => asset('storage/' . str_replace('\\', '/', $m)), $mediaList);
      @endphp

      <div class="insta-post">
        <!-- Header -->
        <div class="header">
          <div class="user-info">
            <a href="{{ url('/business/profile/' . $post->signup->signup_id) }}">
              <img src="{{ $post->signup->profile_pic ?? asset('img/profile.png') }}" alt="profile">
            </a>
            <div class="username">{{ $post->signup->username }}</div>
          </div>
          <i class="fa fa-ellipsis-h more-icon"></i>
        </div>

        <!-- Media Grid -->
        @if(count($mediaList))
        <div class="row g-2 mb-2 post-image-grid">
          @foreach($mediaList as $index => $media)
            @if($index < 4)
            <div class="col-6">
              <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-0 position-relative">
                  <a href="#"
                    data-bs-toggle="modal"
                    data-bs-target="#imageModal"
                    data-images="{{ json_encode($mediaUrls) }}"
                    data-index="{{ $index }}"
                    onclick="openFromAttribute(this)">
                    <img src="{{ asset('storage/' . str_replace('\\', '/', $media)) }}" class="img-fluid rounded" style="height: 250px; object-fit: cover; width: 100%;" />
                    @if($index === 3 && count($mediaList) > 4)
                    <div class="overlay">
                      +{{ count($mediaList) - 4 }}
                    </div>
                    @endif
                  </a>
                </div>
              </div>
            </div>
            @endif
          @endforeach
        </div>
        @endif

        <!-- Caption -->
        <div class="caption">{{ $post->content }}</div>

        <!-- Footer actions -->
        <div class="actions">
          <div class="left-icons">
            <!-- Heart Icon - Green color for liked state -->
            <i class="fa{{ $post->liked ?? false ? 's' : 'r' }} fa-heart" style="color: #198754;"></i>
            <i class="fa-regular fa-comment"></i>
            <!-- Removed the share icon from the footer -->
          </div>
        </div>
      </div>
      @endforeach

    </div>
  </div>
@else
  <div class="text-center text-muted mt-4">No posts yet.</div>
@endif

<style>
  .insta-post {
    background: #fff;
    border: 1px solid #dbdbdb;
    border-radius: 12px;
    margin-bottom: 20px;
    font-family: 'Segoe UI', sans-serif;
    overflow: hidden;
  }

  .insta-post .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 14px;
  }

  .insta-post .user-info {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .insta-post .user-info img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
  }

  .insta-post .username {
    font-weight: bold;
    font-size: 14px;
  }

  .insta-post .more-icon {
    font-size: 18px;
    cursor: pointer;
  }

  .insta-post .caption {
    font-size: 14px;
    padding: 10px 14px;
    color: #333;
  }

  .insta-post .actions {
    display: flex;
    justify-content: space-between;
    padding: 10px 14px;
  }

  .insta-post .actions .left-icons {
    display: flex;
    gap: 14px;
  }

  .insta-post .actions i {
    font-size: 20px;
    cursor: pointer;
  }

  .insta-post .bookmark {
    font-size: 18px;
  }

  .post-image-grid .col-6 {
    padding: 4px;
  }

  .post-image-grid img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 8px;
  }

  .post-image-grid .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 20px;
    border-radius: 8px;
  }
</style>
