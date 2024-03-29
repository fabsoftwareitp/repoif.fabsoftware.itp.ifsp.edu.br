@guest
    <div class="access-links">
        <x-button
            class="user-actions__publish-button"
            type="link"
            href="{{ route('project.create') }}"
            text="Publicar"
            :icon="asset('img/icons/publish-icon.svg')"
        ></x-button>
        <x-button
            class="button--outlined"
            type="link"
            :href="route('login')"
            text="Entrar"
            :svg="file_get_contents(public_path('img/icons/login-icon.svg'))"
        ></x-button>
        <x-button
            type="link"
            :href="route('register')"
            text="Cadastrar"
            :icon="asset('img/icons/register-icon.svg')"
        ></x-button>
    </div>
@else
    <div class="actions">
        <x-button
            class="user-actions__publish-button"
            type="link"
            href="{{ route('project.create') }}"
            text="Publicar"
            :icon="asset('img/icons/publish-icon.svg')"
        ></x-button>
        <x-user-card></x-user-card>
    </div>
@endguest
