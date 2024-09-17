<div class="text-white navbar bg-primary">
  <div class="navbar-start">
    <div class="dropdown">
      <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /></svg>
      </div>

      {{-- Responsive Navbar --}}
      <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 text-black lg:text-white rounded-box w-72">
        <li>
            <details>
              <summary>
                Gerenciar Projetos
            </summary>
              <ul class="p-2 font-normal text-black w-52">
                <li><a href="{{ route('projects.index') }}" class="flex flex-row justify-between w-full">Gerenciar Projetos</a></li>
                <li><a href="{{ route('reports.projects.estimate-earnings') }}" class="flex flex-row justify-between w-full">Estimar ganhos</a></li>
                <li><a href="{{ route('reports.projects.manage-receivements') }}" class="flex flex-row justify-between w-full">Gerenciar Recebimentos</a></li>
              </ul>
            </details>
          </li>
          <li>
              <details>
                <summary>
                    Registro de Ponto
                </summary>
                <ul class="p-2 font-normal text-black w-52">
                  <li><a href="{{ route('punch-clock.register') }}" class="flex flex-row justify-between w-full">Registrar ponto</a></li>
                </ul>
              </details>
          </li>
      </ul>
    </div>
    <a class="text-3xl btn btn-ghost">
        <span class="text-2xl">
            Working
        </span>
    </a>

    <span class="hidden h-8 mx-2 border lg:flex"></span>

    <ul class="hidden px-2 font-medium lg:flex menu menu-horizontal">
        <li>
            <details>
              <summary>
                <i class="text-xl fa-solid fa-layer-group"></i>
                Gerenciar Projetos
              </summary>
              <ul class="absolute z-50 w-64 p-2 font-normal text-black">
                <li><a href="{{ route('projects.index') }}" class="flex flex-row justify-between w-full">Gerenciar Projetos</a></li>
                <li><a href="{{ route('reports.projects.estimate-earnings') }}" class="flex flex-row justify-between w-full">Estimar ganhos</a></li>
                <li><a href="{{ route('reports.projects.manage-receivements') }}" class="flex flex-row justify-between w-full">Gerenciar Recebimentos</a></li>
              </ul>
            </details>
        </li>
        <li>
            <details>
              <summary>
                <i class="text-xl fa-solid fa-bars"></i>
                Registro de Ponto
              </summary>
              <ul class="absolute z-50 w-64 p-2 font-normal text-black">
                <li><a href="{{ route('punch-clock.register') }}" class="flex flex-row justify-between w-full">Registrar ponto</a></li>
              </ul>
            </details>
        </li>
      </ul>
  </div>
  <div class="hidden navbar-center lg:flex">
  </div>
  <div class="navbar-end">
      <div class="dropdown dropdown-end">
          <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
            <div class="w-10 rounded-full bg-slate-100 text-primary">
                <p class="flex items-center justify-center w-full h-full">{{ Auth::user()->name[0] }}</p>
            </div>
          </div>
          <ul tabindex="0" class="dropdown-content mt-3 z-[1] p-2 shadow first-letter:rounded-box w-52 rounded-lg">
                <li>
                    <a href="{{ route('users.profile') }}" type="button" class="flex flex-row justify-start float-left w-full btn btn-sm">
                        <i class="fa-regular fa-id-badge"></i>
                        Perfil
                    </a>
                </li>
                <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="flex flex-row justify-start float-left w-full btn btn-sm">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        Sair
                    </button>
                </form>
            </li>
          </ul>
      </div>
  </div>
</div>

<script>
  const detailsElements = document.querySelectorAll('details');

  detailsElements.forEach(detailsElement => {
    detailsElement.addEventListener('click', function() {
      detailsElements.forEach(element => {
        if (element !== detailsElement) {
          element.removeAttribute('open');
        }
      });
    });
  });
</script>
