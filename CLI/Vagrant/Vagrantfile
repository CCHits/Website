Vagrant.configure(2) do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.provision "shell", path: "./run_setup.sh"
  config.vm.provision "shell", run: "always", path: "./run_showmaker.sh"
end
