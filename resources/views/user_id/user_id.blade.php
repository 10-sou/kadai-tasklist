<div class="mt-4">
    
    
    @if (isset($tasks))
        <ul class="list-none">
            @foreach ($tasks as $task)
                <li class="flex items-start gap-x-2 mb-4">
                    {{-- 投稿の作成日時の表示 --}}
                    <div>
                        <span class="text-muted text-gray-500">posted at {{ $task->created_at }}</span>
                    </div>
                    <div>
                        <div>
                            {{-- 投稿内容 --}}
                            <p class="mb-0">{!! nl2br(e($task->content)) !!}</p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        {{-- ページネーションのリンク --}}
        {{ $tasks->links() }}
    @endif
</div>
