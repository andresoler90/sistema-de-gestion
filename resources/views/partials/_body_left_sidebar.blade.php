<!-- Sidebar  -->
<div class="iq-sidebar">
    <div class="iq-sidebar-logo d-flex justify-content-between">
        <a href="{{ route('home') }}">
            <img src="{{ asset('assets/images/logo.png') }}" class="img-fluid" alt="">
            <span>{{env("APP_NAME")}}</span>
        </a>
        <div class="iq-menu-bt align-self-center">
            <div class="wrapper-menu">
                <div class="line-menu half start"></div>
                <div class="line-menu"></div>
                <div class="line-menu half end"></div>
            </div>
        </div>
    </div>

    @php
        $MyNavBar = \Menu::make('MenuList', function ($menu) {
            $menu->raw(__('Menu'), ['class' => 'iq-menu-title'])->prepend('<i class="ri-separator"></i>');

            if(Auth::user()->roles_id==1){ //Administrador
                $menu->add('<span>'.__('Gestionar').'</span>', ['class' => ''])
                    ->prepend('<i class="fa fa-database" aria-hidden="true"></i>')
                    ->nickname('admin')
                    ->link->attr(["class" => "nav-link iq-waves-effect"])
                    ->href('#admin');

                $menu->admin->add('<span>'.__('Usuarios').'</span>', ['route' => 'users.index'] )
                    ->active('admin/cruds/users/*')
                    ->link->attr(['class' => '']);

                $menu->admin->add('<span>'.__('Clientes').'</span>', ['route' => 'clients.index'] )
                    ->active('admin/cruds/clients/*')
                    ->link->attr(['class' => '']);

                $menu->admin->add('<span>'.__('Tareas').'</span>', ['route' => 'tasks.index'] )
                    ->active('admin/cruds/tasks/*')
                    ->link->attr(['class' => '']);

                $menu->admin->add('<span>'.__('Documentos').'</span>', ['route' => 'documents.index'] )
                    ->active('admin/cruds/documents/*')
                    ->link->attr(['class' => '']);

                $menu->admin->add('<span>'.__('Palabras configurables').'</span>', ['route' => 'configurable.words.index'] )
                    ->active('admin/cruds/configurable_words/*')
                    ->link->attr(['class' => '']);

                $menu->admin->add('<span>'.__('Etapa Comercial').'</span>', ['route' => 'commercial.register.index'] )
                    ->active('admin/registers/commercial/*')
                    ->link->attr(['class' => '']);

                $menu->admin->add('<span>'.__('Lista de precios').'</span>', ['route' => 'price.list.index'] )
                    ->active('admin/pricelist/*')
                    ->link->attr(['class' => '']);

                    $menu->admin->add('<span>'.__('Logs').'</span>', ['url' => 'admin/user-activity'] )
                    ->active('admin/user-activity/*')
                    ->link->attr(['class' => '']);

                $menu->add('<span>'.__('Configuraciones').'</span>', ['class' => ''])
                    ->prepend('<i class="fa fa-cog" aria-hidden="true"></i>')
                    ->nickname('settings')
                    ->link->attr(["class" => "nav-link iq-waves-effect"])
                    ->href('#Configuraciones');
                $menu->settings->add('<span>'.__('Campos por cliente').'</span>', ['route' => 'settings.index'] )
                    ->active('settings/index')
                    ->link->attr(['class' => '']);
                $menu->settings->add('<span>'.__('Alertas').'</span>', ['route' => 'client.config.alert'] )
                    ->active('client/config/alerts')
                    ->link->attr(['class' => '']);
            }

            if( Auth::user()->roles_id==1 || Auth::user()->roles_id==5 ||
                Auth::user()->hasPermission('management_report') ||
                Auth::user()->hasPermission('ans_report') ||
                Auth::user()->hasPermission('dashboard_report') ||
                Auth::user()->hasPermission('ticket_status_report')
            ){ // Administrador, cliente o tiene algun permiso de reporte
                $menu->add('<span>'.__('Reportes').'</span>', ['class' => ''])
                    ->prepend('<i class="fa fa-pie-chart" aria-hidden="true"></i>')
                    ->nickname('reports')
                    ->link->attr(["class" => "nav-link iq-waves-effect"])
                    ->href('#reports');
            }

            if( Auth::user()->roles_id==1 || Auth::user()->roles_id==5 || Auth::user()->hasPermission('ticket_status_report')){ // Administrador, cliente
                $menu->reports->add('<span>'.__('Estado de tiquetes').'</span>', ['route' => 'reports.request.index'] )
                    ->active('reports/requests/*')
                    ->link->attr(['class' => '']);
            }

            if(Auth::user()->roles_id==1 || Auth::user()->hasPermission('management_report')){
                $menu->reports->add('<span>'.__('Gestión').'</span>', ['route' => 'reports.management.index'] )
                    ->active('reports/management/*')
                    ->link->attr(['class' => '']);

            }
            if(Auth::user()->roles_id==1 || Auth::user()->hasPermission('ans_report')){
                $menu->reports->add('<span>'.__('ANS').'</span>', ['route' => 'reports.ans.index'] )
                    ->active('reports/ans/*')
                    ->link->attr(['class' => '']);
            }

            if(Auth::user()->roles_id==1){ // Administrador

                $menu->reports->add('<span>'.__('Gestión interna').'</span>', ['route' => 'reports.internal.management.index'] )
                    ->active('reports/internal_management/*')
                    ->link->attr(['class' => '']);
                $menu->reports->add('<span>'.__('Calidad').'</span>', ['route' => 'reports.quality.index'] )
                    ->active('reports/quality/*')
                    ->link->attr(['class' => '']);
            }

            if(Auth::user()->roles_id==1 || Auth::user()->hasPermission('dashboard_report')){
                $menu->reports->add('<span>'.__('Dashboard').'</span>', ['route' => 'reports.dashboard.index'] )
                    ->active('reports/dashboard/*')
                    ->link->attr(['class' => '']);
            }

            if(Auth::user()->roles_id==2){ // Analista
                $menu->add('<span>'.__("Tareas").'</span>', ['class' => '','route' => 'analyst.tasks.index'])
                    ->prepend('<i class="fas fa-tasks"></i>')
                    ->active('analyst/tasks/*')
                    ->link->attr(['class' => 'iq-waves-effect']);
            }
            if(Auth::user()->roles_id==5 || Auth::user()->hasPermission('request_provider')){ //Cliente o permiso de solicitar proveedores
                $menu->add('<span>'.__("Solicitudes").'</span>', ['class' => '','route' => 'registers.index'])
                    ->prepend('<i class="fas fa-business-time"></i>')
                    ->active('client/register/*')
                    ->link->attr(['class' => 'iq-waves-effect']);
            }
            if(in_array(Auth::user()->roles_id,[2,3,4])){
                $menu->add('<span>'.__("Etapa Comercial").'</span>', ['class' => '','route' => 'commercial.register.index'])
                    ->prepend('<i class="fas fa-business-time"></i>')
                    ->active('commercial/register/*')
                    ->link->attr(['class' => 'iq-waves-effect']);
            }
            if(Auth::user()->roles_id==5){ // Cliente
                $menu->add('<span>'.__("Solicitudes Escaladas").'</span>', ['class' => '','route' => 'scaled.registers.index'])
                    ->prepend('<i class="fas fa-business-time"></i>')
                    ->active('client/scaled/*')
                    ->link->attr(['class' => 'iq-waves-effect']);
            }
            if(Auth::user()->roles_id==3){ // Coordinador

                $menu->add('<span>'.__('Tareas').'</span>', ['class' => ''])
                    ->prepend('<i class="fas fa-thumbtack" aria-hidden="true"></i>')
                    ->nickname('tasks')
                    ->link->attr(["class" => "nav-link iq-waves-effect"])
                    ->href('#tasks');

                $menu->tasks->add('<span>'.__("Prioridad").'</span>', ['class' => '','route' => 'priority.tasks.index'])
                    // ->prepend('<i class="fas fa-clone"></i>')
                    ->active('coordinator/priority/*')
                    ->link->attr(['class' => 'iq-waves-effect']);

                $menu->tasks->add('<span>'.__("Gestión Documental").'</span>', ['class' => '','route' => 'document.management.tasks.index'])
                    // ->prepend('<i class="fas fa-clone"></i>')
                    ->active('coordinator/priority/*')
                    ->link->attr(['class' => 'iq-waves-effect']);

                $menu->add('<span>'.__('Solicitudes').'</span>', ['class' => ''])
                    ->prepend('<i class="fas fa-clipboard" aria-hidden="true"></i>')
                    ->nickname('registers')
                    ->link->attr(["class" => "nav-link iq-waves-effect"])
                    ->href('#registers');

                $menu->registers->add('<span>'.__("Escaladas").'</span>', ['class' => '','route' => 'management.scaled.index'])
                    // ->prepend('<i class="fas fa-business-time"></i>')
                    ->active('coordinator/scaled/*')
                    ->link->attr(['class' => 'iq-waves-effect']);

                $menu->registers->add('<span>'.__("Seguimientos").'</span>', ['class' => '','route' => 'tracking.tasks.index'])
                    // ->prepend('<i class="fas fa-layer-group"></i>')
                    ->active('coordinator/tracking/*')
                    ->link->attr(['class' => 'iq-waves-effect']);

                $menu->registers->add('<span>'.__("Suspendidas").'</span>', ['class' => '','route' => 'suspended.index'])
                    ->active('coordinator/suspended/*')
                    ->link->attr(['class' => 'iq-waves-effect']);
            }
        })->filter(function ($item) {
            return $item;
        });
    @endphp

    <div id="sidebar-scrollbar">
        <nav class="iq-sidebar-menu">
            <ul id="iq-sidebar-toggle" class="iq-menu">
                @include(config('laravel-menu.views.bootstrap-items'), ['items' => $MyNavBar->roots()])
            </ul>
        </nav>
        <div class="p-3"></div>
    </div>
</div>
