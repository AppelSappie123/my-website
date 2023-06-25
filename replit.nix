{ pkgs }: {
  deps = [
    pkgs.apacheHttpdPackages.php
    pkgs.php80Packages.composer
    pkgs.nodePackages.vscode-langservers-extracted
    pkgs.nodePackages.typescript-language-server  
  ];
}